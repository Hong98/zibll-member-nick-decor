<?php
/**
 * Zibll Member Nickname & Badge Pro - Hiển thị ngoài Frontend & Giao diện Shop / Tủ đồ
 *
 * @package Zibll_Member_Nick_Decor
 */

if (!defined('ABSPATH')) {
    exit;
}

class ZND_Renderer {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Nạp CSS & JS ngoài Frontend
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));

        // Xuất CSS động của các màu nick và animation vào thẻ <head>
        add_action('wp_head', array($this, 'output_dynamic_styles'), 90);

        // Can thiệp hiển thị tên thành viên trong Theme Zibll (Diễn đàn, Tác giả, Widget, Header,...)
        add_filter('user_show_name', array($this, 'filter_user_show_name'), 20, 2);

        // Can thiệp hiển thị tên thành viên trong Bình luận
        add_filter('comment_header', array($this, 'filter_comment_header'), 20, 2);

        // Nút mở nhanh trên thanh Header của Theme Zibll
        add_filter('zib_nav_radius_button', array($this, 'filter_nav_radius_button'), 20, 2);

        // Tích hợp tab vào Trung tâm người dùng (User Center - /user)
        add_filter('user_ctnter_main_tabs_array', array($this, 'add_user_center_tab'), 20);
        add_filter('zib_user_center_page_sidebar_button_1_args', array($this, 'add_user_center_sidebar_button'), 20);
        add_filter('main_user_tab_content_nick_decor', array($this, 'render_user_tab_content'));

        // Tích hợp tab vào Trang tác giả (Author Page - /author/xxx)
        add_filter('author_main_tabs_array', array($this, 'add_author_main_tab'), 20, 2);
        add_filter('main_author_tab_content_nick_decor', array($this, 'render_author_tab_content'));

        // Xuất Modal Cửa hàng & Tủ đồ ở cuối trang (wp_footer)
        add_action('wp_footer', array($this, 'output_shop_modal'), 99);

        // Đăng ký Shortcode [zibll_nick_shop]
        add_shortcode('zibll_nick_shop', array($this, 'shortcode_nick_shop'));
    }

    /**
     * Kiểm tra tính năng có đang bật không
     */
    public static function is_enabled() {
        if (function_exists('_pz')) {
            return (bool)_pz('znd_enabled', true);
        }
        return true;
    }

    /**
     * Nạp Assets CSS & JS
     */
    public function enqueue_frontend_assets() {
        if (!self::is_enabled()) {
            return;
        }

        $css_ver = file_exists(ZND_PATH . 'assets/css/frontend.css') ? filemtime(ZND_PATH . 'assets/css/frontend.css') : ZND_VERSION;
        $js_ver  = file_exists(ZND_PATH . 'assets/js/frontend.js') ? filemtime(ZND_PATH . 'assets/js/frontend.js') : ZND_VERSION;

        wp_enqueue_style(
            'znd-frontend',
            ZND_URL . 'assets/css/frontend.css',
            array(),
            $css_ver
        );

        wp_enqueue_script(
            'znd-frontend',
            ZND_URL . 'assets/js/frontend.js',
            array('jquery'),
            $js_ver,
            true
        );

        $current_user_id = get_current_user_id();
        $equipped_icon  = ZND_User::get_equipped_icon($current_user_id);
        $equipped_color = ZND_User::get_equipped_color($current_user_id);

        wp_localize_script('znd-frontend', 'znd_vars', array(
            'ajax_url'                 => admin_url('admin-ajax.php'),
            'nonce'                    => wp_create_nonce('znd_nonce'),
            'is_logged_in'             => is_user_logged_in(),
            'user_id'                  => $current_user_id,
            'equipped_icon'            => $equipped_icon,
            'equipped_color'           => $equipped_color,
            'currency_name'            => ZND_User::get_currency_name(),
            'user_balance'             => ZND_User::get_user_currency_balance($current_user_id),
            'enable_mobile_header_btn' => function_exists('_pz') ? (_pz('znd_enable_mobile_header_btn', true) ? 1 : 0) : 1,
            'enable_mobile_drawer_btn' => function_exists('_pz') ? (_pz('znd_enable_mobile_drawer_btn', true) ? 1 : 0) : 1,
            'i18n'                     => array(
                'confirm_buy'   => __('Bạn có chắc chắn muốn mua vật phẩm này không?', 'zibll-nick-decor'),
                'buying'        => __('Đang xử lý mua...', 'zibll-nick-decor'),
                'equipping'     => __('Đang trang bị...', 'zibll-nick-decor'),
                'unequipping'   => __('Đang tháo...', 'zibll-nick-decor'),
                'login_require' => __('Vui lòng đăng nhập để mở tủ đồ và mua sắm!', 'zibll-nick-decor'),
            ),
        ));
    }

    /**
     * Xuất CSS động cho toàn bộ bảng màu và hiệu ứng
     */
    public function output_dynamic_styles() {
        if (!self::is_enabled()) {
            return;
        }

        $colors = ZND_Defaults::get_all_colors();
        $icons  = ZND_Defaults::get_all_icons();

        echo "\n<!-- Zibll Member Nickname & Badge Pro Dynamic Styles -->\n";
        echo "<style id=\"znd-dynamic-styles\">\n";

        // Định nghĩa class CSS cho từng màu
        foreach ($colors as $id => $color) {
            $style = !empty($color['css_style']) ? $color['css_style'] : '';
            if (!empty($style)) {
                echo ".znd-color-{$id} { {$style} }\n";
            }
        }

        // Tùy biến CSS nâng cao từ Admin
        if (function_exists('_pz')) {
            if (!_pz('znd_enable_mobile_header_btn', true)) {
                echo "body .navbar-header .znd-mobile-header-btn, body .navbar-toggle.znd-mobile-header-btn, a.navbar-toggle.znd-mobile-header-btn { display: none !important; }\n";
            }
            if (!_pz('znd_enable_mobile_drawer_btn', true)) {
                echo "body .mobile-navbar .znd-mobile-drawer-btn, body .toggle-radius.znd-mobile-drawer-btn, a.toggle-radius.znd-mobile-drawer-btn { display: none !important; }\n";
            }

            $custom_css = _pz('znd_custom_css', '');
            if (!empty($custom_css)) {
                echo $custom_css . "\n";
            }
        }

        echo "</style>\n";
    }

    /**
     * Lọc tên thành viên hiển thị qua filter user_show_name của Theme Zibll
     */
    public function filter_user_show_name($html, $user_id) {
        if (!self::is_enabled() || empty($user_id) || empty($html)) {
            return $html;
        }

        $icon_id  = ZND_User::get_equipped_icon($user_id);
        $color_id = ZND_User::get_equipped_color($user_id);

        if (empty($icon_id) && empty($color_id)) {
            return $html;
        }

        $icon_html = '';
        if (!empty($icon_id)) {
            $icon_data = ZND_Defaults::get_icon($icon_id);
            if ($icon_data && !empty($icon_data['svg'])) {
                $anim_class = !empty($icon_data['animation']) && $icon_data['animation'] !== 'none' ? ' znd-anim-' . esc_attr($icon_data['animation']) : '';
                $icon_name  = esc_attr($icon_data['name']);
                $icon_html  = '<span class="znd-nick-icon znd-icon-' . esc_attr($icon_id) . $anim_class . '" title="' . $icon_name . '">' . $icon_data['svg'] . '</span>';
            }
        }

        $color_class = '';
        if (!empty($color_id)) {
            $color_data = ZND_Defaults::get_color($color_id);
            if ($color_data) {
                $anim_class = !empty($color_data['animation']) && $color_data['animation'] !== 'none' ? ' znd-anim-' . esc_attr($color_data['animation']) : '';
                $color_class = ' znd-color-' . esc_attr($color_id) . $anim_class;
            }
        }

        // Tìm thẻ <a class="display-name..." để chèn icon và thêm class màu
        // Cấu trúc Zibll chuẩn: <a class="display-name text-ellipsis $class" href="$url">$vip_icon$display_name</a>
        if (preg_match('/(<a[^>]*class=["\'][^"\']*display-name[^"\']*["\'][^>]*>)(.*?)(<\/a>)/is', $html, $matches)) {
            $open_tag  = $matches[1];
            $inner_con = $matches[2];
            $close_tag = $matches[3];

            // Thêm class màu vào thẻ <a> nếu có
            if (!empty($color_class)) {
                $open_tag = preg_replace('/class=["\']([^"\']*)["\']/', 'class="$1' . $color_class . '"', $open_tag);
            }

            // Chèn icon đầu nick vào ngay sau icon VIP (nếu có) hoặc ngay đầu nội dung thẻ <a>
            if (!empty($icon_html)) {
                // Nếu bên trong có huy hiệu VIP (chứa class mr3 hoặc zibpay-vip), chèn ngay sau VIP icon
                if (preg_match('/(<span[^>]*class=["\'][^"\']*mr3[^"\']*["\'][^>]*>.*?<\/span>)/is', $inner_con, $vip_m)) {
                    $inner_con = str_replace($vip_m[1], $vip_m[1] . $icon_html, $inner_con);
                } else {
                    $inner_con = $icon_html . $inner_con;
                }
            }

            $new_a = $open_tag . $inner_con . $close_tag;
            $html = str_replace($matches[0], $new_a, $html);
        }

        return $html;
    }

    /**
     * Lọc tên thành viên trong bình luận (comment_header)
     */
    public function filter_comment_header($html, $comment) {
        if (!self::is_enabled() || empty($comment->user_id) || empty($html)) {
            return $html;
        }

        $user_id  = $comment->user_id;
        $icon_id  = ZND_User::get_equipped_icon($user_id);
        $color_id = ZND_User::get_equipped_color($user_id);

        if (empty($icon_id) && empty($color_id)) {
            return $html;
        }

        $icon_html = '';
        if (!empty($icon_id)) {
            $icon_data = ZND_Defaults::get_icon($icon_id);
            if ($icon_data && !empty($icon_data['svg'])) {
                $anim_class = !empty($icon_data['animation']) && $icon_data['animation'] !== 'none' ? ' znd-anim-' . esc_attr($icon_data['animation']) : '';
                $icon_name  = esc_attr($icon_data['name']);
                $icon_html  = '<span class="znd-nick-icon znd-icon-' . esc_attr($icon_id) . $anim_class . '" title="' . $icon_name . '">' . $icon_data['svg'] . '</span>';
            }
        }

        $color_class = '';
        if (!empty($color_id)) {
            $color_data = ZND_Defaults::get_color($color_id);
            if ($color_data) {
                $anim_class = !empty($color_data['animation']) && $color_data['animation'] !== 'none' ? ' znd-anim-' . esc_attr($color_data['animation']) : '';
                $color_class = ' znd-color-' . esc_attr($color_id) . $anim_class;
            }
        }

        // Tìm thẻ <a class="...text-ellipsis..." trong header bình luận
        if (preg_match('/(<a[^>]*href=["\'][^"\']*["\'][^>]*class=["\'][^"\']*(?:text-ellipsis|font-bold)[^"\']*["\'][^>]*>)(.*?)(<\/a>)/is', $html, $matches)) {
            $open_tag  = $matches[1];
            $inner_con = $matches[2];
            $close_tag = $matches[3];

            if (!empty($color_class)) {
                $open_tag = preg_replace('/class=["\']([^"\']*)["\']/', 'class="$1' . $color_class . '"', $open_tag);
            }

            if (!empty($icon_html)) {
                $inner_con = $icon_html . $inner_con;
            }

            $new_a = $open_tag . $inner_con . $close_tag;
            $html = str_replace($matches[0], $new_a, $html);
        }

        return $html;
    }

    /**
     * Nút mở nhanh trên thanh Header của Theme Zibll (cạnh nút Dark mode & Ngôn ngữ)
     */
    public function filter_nav_radius_button($radius_but, $user_id) {
        if (!self::is_enabled()) {
            return $radius_but;
        }

        $show_btn = function_exists('_pz') ? _pz('znd_enable_user_menu_btn', true) : true;
        if (!$show_btn) {
            return $radius_but;
        }

        $btn = '<a href="javascript:;" data-action="zibll_nick_decor_modal" class="toggle-radius open-nick-decor-modal ml10" title="' . esc_attr__('Cửa hàng màu & Icon nick', 'zibll-nick-decor') . '"><i class="fa fa-paint-brush"></i></a>';

        return $radius_but . $btn;
    }

    /**
     * Thêm tab vào Trung tâm người dùng (/user)
     */
    public function add_user_center_tab($tabs_array) {
        if (!self::is_enabled()) {
            return $tabs_array;
        }

        $show_tab = function_exists('_pz') ? _pz('znd_enable_author_tab', true) : true;
        if (!$show_tab) {
            return $tabs_array;
        }

        $tabs_array['nick_decor'] = array(
            'title'    => __('Màu & Icon', 'zibll-nick-decor'),
            'nav_attr' => 'drawer-title="' . __('Cửa hàng màu & Icon nick', 'zibll-nick-decor') . '"',
            'loader'   => '<div class="text-center padding-20 muted-color"><i class="fa fa-spinner fa-spin fa-2x"></i></div>',
        );

        return $tabs_array;
    }

    /**
     * Thêm nút vào Sidebar của Trung tâm người dùng (/user - Dịch vụ của tôi)
     */
    public function add_user_center_sidebar_button($buttons) {
        if (!self::is_enabled()) {
            return $buttons;
        }

        $show_tab = function_exists('_pz') ? _pz('znd_enable_author_tab', true) : true;
        if (!$show_tab) {
            return $buttons;
        }

        $buttons[] = array(
            'html' => '',
            'icon' => '<i class="fa fa-paint-brush" style="color:#f59e0b;"></i>',
            'name' => __('Màu & Icon', 'zibll-nick-decor'),
            'tab'  => 'nick_decor',
        );

        return $buttons;
    }

    /**
     * Render nội dung tab trong Trung tâm người dùng (/user)
     */
    public function render_user_tab_content() {
        return self::render_shop_html(get_current_user_id(), false);
    }

    /**
     * Thêm tab "Màu & Icon" vào trang cá nhân của Zibll
     */
    public function add_author_main_tab($tabs_args, $author_id) {
        if (!self::is_enabled()) {
            return $tabs_args;
        }

        $show_tab = function_exists('_pz') ? _pz('znd_enable_author_tab', true) : true;
        if (!$show_tab) {
            return $tabs_args;
        }

        $tabs_args['nick_decor'] = array(
            'title'         => '<i class="fa fa-paint-brush mr3"></i>' . __('Màu & Icon', 'zibll-nick-decor'),
            'content_class' => 'znd-author-tab-pane',
            'route'         => true,
            'loader'        => '<div class="text-center padding-20 muted-color"><i class="fa fa-spinner fa-spin fa-2x"></i></div>',
        );

        return $tabs_args;
    }

    /**
     * Render nội dung tab Màu & Icon trong trang cá nhân
     */
    public function render_author_tab_content() {
        global $wp_query;
        $curauth = $wp_query->get_queried_object();
        $target_user_id = !empty($curauth->ID) ? $curauth->ID : get_current_user_id();

        return self::render_shop_html($target_user_id, false);
    }

    /**
     * Shortcode [zibll_nick_shop]
     */
    public function shortcode_nick_shop($atts) {
        if (!self::is_enabled()) {
            return '';
        }
        return self::render_shop_html(get_current_user_id(), false);
    }

    /**
     * Xuất Modal Cửa hàng & Tủ đồ ra footer
     */
    public function output_shop_modal() {
        if (!self::is_enabled()) {
            return;
        }
        $user_id = get_current_user_id();
        ?>
        <!-- Zibll Member Nickname & Badge Pro Modal -->
        <div id="znd-shop-modal" class="znd-modal-backdrop" style="display:none;" aria-hidden="true">
            <div class="znd-modal-dialog" role="dialog" aria-modal="true">
                <div class="znd-modal-header">
                    <div class="znd-modal-title">
                        <span class="znd-title-icon"><i class="fa fa-diamond"></i></span>
                        <div>
                            <h3 class="znd-heading"><?php esc_html_e('Cửa hàng màu & Icon nick', 'zibll-nick-decor'); ?></h3>
                            <p class="znd-subheading"><?php esc_html_e('Tùy biến phong cách nickname độc bản và nổi bật cho thành viên', 'zibll-nick-decor'); ?></p>
                        </div>
                    </div>
                    <button type="button" class="znd-modal-close" aria-label="<?php esc_attr_e('Đóng', 'zibll-nick-decor'); ?>">&times;</button>
                </div>

                <div class="znd-modal-body">
                    <?php echo self::render_shop_html($user_id, true); ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render toàn bộ giao diện Shop & Tủ đồ (Dùng chung cho Modal, Author Tab và Shortcode)
     */
    public static function render_shop_html($user_id = 0, $is_modal = false) {
        $icons  = ZND_Defaults::get_all_icons();
        $colors = ZND_Defaults::get_all_colors();

        $is_owner = is_user_logged_in() && ($user_id === get_current_user_id());
        $inv      = $user_id ? ZND_User::get_inventory($user_id) : array('icons' => array(), 'colors' => array());

        $equipped_icon  = $user_id ? ZND_User::get_equipped_icon($user_id) : '';
        $equipped_color = $user_id ? ZND_User::get_equipped_color($user_id) : '';

        $user_data = $user_id ? get_userdata($user_id) : null;
        $user_name = $user_data ? $user_data->display_name : __('Thành viên', 'zibll-nick-decor');
        $avatar    = $user_id && function_exists('zib_get_data_avatar') ? zib_get_data_avatar($user_id) : '<span class="avatar-default"><i class="fa fa-user-circle"></i></span>';

        $balance       = ZND_User::get_user_currency_balance($user_id);
        $currency_name = ZND_User::get_currency_name();

        ob_start();
        ?>
        <div class="znd-shop-wrapper <?php echo $is_modal ? 'znd-in-modal' : 'znd-standalone'; ?>">

            <!-- HỘP XEM TRƯỚC THỜI GIAN THỰC (LIVE PREVIEW) -->
            <div class="znd-live-preview-box">
                <div class="znd-preview-label">
                    <i class="fa fa-eye fa-fw"></i> <?php esc_html_e('Xem trước diện mạo của bạn:', 'zibll-nick-decor'); ?>
                </div>
                <div class="znd-preview-card">
                    <div class="znd-preview-avatar"><?php echo $avatar; ?></div>
                    <div class="znd-preview-info">
                        <div class="znd-preview-name-line">
                            <span id="znd-preview-icon" class="znd-nick-icon">
                                <?php
                                if (!empty($equipped_icon) && isset($icons[$equipped_icon])) {
                                    $i_data = $icons[$equipped_icon];
                                    $anim_class = !empty($i_data['animation']) && $i_data['animation'] !== 'none' ? ' znd-anim-' . esc_attr($i_data['animation']) : '';
                                    echo '<span class="' . $anim_class . '">' . $i_data['svg'] . '</span>';
                                }
                                ?>
                            </span>
                            <span id="znd-preview-name" class="znd-preview-display-name <?php echo !empty($equipped_color) ? 'znd-color-' . esc_attr($equipped_color) : ''; ?>">
                                <?php echo esc_html($user_name); ?>
                            </span>
                        </div>
                        <div class="znd-preview-meta muted-2-color">
                            <span id="znd-preview-desc"><?php esc_html_e('Nhấp vào bất kỳ màu hoặc icon nào bên dưới để thử ngay!', 'zibll-nick-decor'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- THANH THỐNG KÊ TÀI SẢN THÀNH VIÊN -->
                <?php if (is_user_logged_in()): ?>
                <div class="znd-balance-bar">
                    <div class="znd-balance-info">
                        <i class="fa fa-wallet fa-fw text-warning"></i>
                        <span><?php esc_html_e('Số dư hiện có:', 'zibll-nick-decor'); ?></span>
                        <strong id="znd-current-balance" class="text-primary"><?php echo number_format($balance); ?></strong> <?php echo esc_html($currency_name); ?>
                    </div>
                    <?php if (function_exists('zibpay_get_points_pay_link')): ?>
                    <div class="znd-charge-btn">
                        <?php echo zibpay_get_points_pay_link('but but-sm c-yellow radius', '<i class="fa fa-plus-circle mr3"></i>' . __('Nạp thêm', 'zibll-nick-decor')); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="znd-login-notice">
                    <i class="fa fa-sign-in fa-fw"></i> <?php esc_html_e('Vui lòng', 'zibll-nick-decor'); ?>
                    <a href="javascript:;" class="signin-loader text-primary font-bold"><?php esc_html_e('Đăng nhập', 'zibll-nick-decor'); ?></a>
                    <?php esc_html_e('để mua sắm và trang bị màu & icon cho nick của bạn!', 'zibll-nick-decor'); ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- THANH ĐIỀU HƯỚNG TABS -->
            <div class="znd-nav-tabs">
                <button type="button" class="znd-tab-btn active" data-tab="icons">
                    <i class="fa fa-star fa-fw"></i> <?php esc_html_e('Icon đầu nick', 'zibll-nick-decor'); ?>
                    <span class="znd-badge-count"><?php echo count($icons); ?></span>
                </button>
                <button type="button" class="znd-tab-btn" data-tab="colors">
                    <i class="fa fa-magic fa-fw"></i> <?php esc_html_e('Màu sắc nick', 'zibll-nick-decor'); ?>
                    <span class="znd-badge-count"><?php echo count($colors); ?></span>
                </button>
                <?php if (is_user_logged_in()): ?>
                <button type="button" class="znd-tab-btn" data-tab="wardrobe">
                    <i class="fa fa-briefcase fa-fw"></i> <?php esc_html_e('Tủ đồ của tôi', 'zibll-nick-decor'); ?>
                    <span class="znd-badge-count znd-owned-count"><?php echo count($inv['icons']) + count($inv['colors']); ?></span>
                </button>
                <?php endif; ?>
            </div>

            <!-- NỘI DUNG TAB 1: SHOP ICON ĐẦU NICK -->
            <div id="znd-tab-icons" class="znd-tab-content active">
                <div class="znd-grid">
                    <?php foreach ($icons as $id => $item):
                        if (empty($item['status'])) continue;
                        $is_owned    = isset($inv['icons'][$id]);
                        $is_equipped = ($equipped_icon === $id);
                        $anim_class  = !empty($item['animation']) && $item['animation'] !== 'none' ? ' znd-anim-' . esc_attr($item['animation']) : '';
                        $price       = isset($item['price']) ? (float)$item['price'] : 0;
                        $duration    = !empty($item['duration']) ? sprintf(__('%d ngày', 'zibll-nick-decor'), $item['duration']) : __('Vĩnh viễn', 'zibll-nick-decor');
                    ?>
                    <div class="znd-card <?php echo $is_equipped ? 'is-equipped' : ($is_owned ? 'is-owned' : ''); ?>"
                         data-id="<?php echo esc_attr($id); ?>"
                         data-type="icon"
                         data-name="<?php echo esc_attr($item['name']); ?>"
                         data-anim="<?php echo esc_attr(!empty($item['animation']) ? $item['animation'] : ''); ?>"
                         data-item-svg="<?php echo esc_attr($item['svg']); ?>">

                        <div class="znd-card-icon-preview">
                            <span class="znd-nick-icon <?php echo $anim_class; ?>">
                                <?php echo $item['svg']; ?>
                            </span>
                        </div>

                        <div class="znd-card-info">
                            <h4 class="znd-card-title"><?php echo esc_html($item['name']); ?></h4>
                            <p class="znd-card-desc"><?php echo esc_html($item['desc']); ?></p>
                            <div class="znd-card-tags">
                                <span class="znd-tag znd-tag-price">
                                    <i class="fa fa-coins"></i> <?php echo $price > 0 ? number_format($price) . ' ' . esc_html($currency_name) : __('Miễn phí', 'zibll-nick-decor'); ?>
                                </span>
                                <span class="znd-tag znd-tag-duration"><i class="fa fa-clock-o"></i> <?php echo esc_html($duration); ?></span>
                            </div>
                        </div>

                        <div class="znd-card-actions">
                            <button type="button" class="znd-btn znd-btn-preview" title="<?php esc_attr_e('Xem thử trên tên bạn', 'zibll-nick-decor'); ?>">
                                <i class="fa fa-eye"></i> <?php esc_html_e('Thử', 'zibll-nick-decor'); ?>
                            </button>

                            <?php if ($is_equipped): ?>
                                <button type="button" class="znd-btn znd-btn-unequip" data-action="unequip" data-type="icon">
                                    <i class="fa fa-check-circle"></i> <?php esc_html_e('Đang dùng (Tháo)', 'zibll-nick-decor'); ?>
                                </button>
                            <?php elseif ($is_owned): ?>
                                <button type="button" class="znd-btn znd-btn-equip" data-action="equip" data-type="icon" data-id="<?php echo esc_attr($id); ?>">
                                    <i class="fa fa-bolt"></i> <?php esc_html_e('Trang bị', 'zibll-nick-decor'); ?>
                                </button>
                            <?php else: ?>
                                <button type="button" class="znd-btn znd-btn-buy" data-action="buy" data-type="icon" data-id="<?php echo esc_attr($id); ?>">
                                    <i class="fa fa-shopping-cart"></i> <?php esc_html_e('Mua ngay', 'zibll-nick-decor'); ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- NỘI DUNG TAB 2: SHOP MÀU SẮC NICK -->
            <div id="znd-tab-colors" class="znd-tab-content">
                <div class="znd-grid">
                    <?php foreach ($colors as $id => $item):
                        if (empty($item['status'])) continue;
                        $is_owned    = isset($inv['colors'][$id]);
                        $is_equipped = ($equipped_color === $id);
                        $anim_class  = !empty($item['animation']) && $item['animation'] !== 'none' ? ' znd-anim-' . esc_attr($item['animation']) : '';
                        $price       = isset($item['price']) ? (float)$item['price'] : 0;
                        $duration    = !empty($item['duration']) ? sprintf(__('%d ngày', 'zibll-nick-decor'), $item['duration']) : __('Vĩnh viễn', 'zibll-nick-decor');
                    ?>
                    <div class="znd-card <?php echo $is_equipped ? 'is-equipped' : ($is_owned ? 'is-owned' : ''); ?>"
                         data-id="<?php echo esc_attr($id); ?>"
                         data-type="color"
                         data-name="<?php echo esc_attr($item['name']); ?>"
                         data-anim="<?php echo esc_attr(!empty($item['animation']) ? $item['animation'] : ''); ?>"
                         data-css="znd-color-<?php echo esc_attr($id); ?>">

                        <div class="znd-card-color-sample">
                            <span class="znd-sample-text znd-color-<?php echo esc_attr($id); ?> <?php echo $anim_class; ?>">
                                <?php echo esc_html($item['name']); ?>
                            </span>
                        </div>

                        <div class="znd-card-info">
                            <h4 class="znd-card-title"><?php echo esc_html($item['name']); ?></h4>
                            <p class="znd-card-desc"><?php echo esc_html($item['desc']); ?></p>
                            <div class="znd-card-tags">
                                <span class="znd-tag znd-tag-price">
                                    <i class="fa fa-coins"></i> <?php echo $price > 0 ? number_format($price) . ' ' . esc_html($currency_name) : __('Miễn phí', 'zibll-nick-decor'); ?>
                                </span>
                                <span class="znd-tag znd-tag-duration"><i class="fa fa-clock-o"></i> <?php echo esc_html($duration); ?></span>
                            </div>
                        </div>

                        <div class="znd-card-actions">
                            <button type="button" class="znd-btn znd-btn-preview" title="<?php esc_attr_e('Xem thử trên tên bạn', 'zibll-nick-decor'); ?>">
                                <i class="fa fa-eye"></i> <?php esc_html_e('Thử', 'zibll-nick-decor'); ?>
                            </button>

                            <?php if ($is_equipped): ?>
                                <button type="button" class="znd-btn znd-btn-unequip" data-action="unequip" data-type="color">
                                    <i class="fa fa-check-circle"></i> <?php esc_html_e('Đang dùng (Tháo)', 'zibll-nick-decor'); ?>
                                </button>
                            <?php elseif ($is_owned): ?>
                                <button type="button" class="znd-btn znd-btn-equip" data-action="equip" data-type="color" data-id="<?php echo esc_attr($id); ?>">
                                    <i class="fa fa-bolt"></i> <?php esc_html_e('Trang bị', 'zibll-nick-decor'); ?>
                                </button>
                            <?php else: ?>
                                <button type="button" class="znd-btn znd-btn-buy" data-action="buy" data-type="color" data-id="<?php echo esc_attr($id); ?>">
                                    <i class="fa fa-shopping-cart"></i> <?php esc_html_e('Mua ngay', 'zibll-nick-decor'); ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- NỘI DUNG TAB 3: TỦ ĐỒ CỦA TÔI -->
            <?php if (is_user_logged_in()): ?>
            <div id="znd-tab-wardrobe" class="znd-tab-content">
                <?php if (empty($inv['icons']) && empty($inv['colors'])): ?>
                    <div class="znd-empty-box">
                        <i class="fa fa-box-open fa-3x muted-3-color"></i>
                        <h4><?php esc_html_e('Tủ đồ của bạn đang trống!', 'zibll-nick-decor'); ?></h4>
                        <p class="muted-color"><?php esc_html_e('Hãy chọn mua những icon và màu nick cực chất ở các tab bên cạnh để làm mới phong cách nhé.', 'zibll-nick-decor'); ?></p>
                    </div>
                <?php else: ?>
                    <div class="znd-wardrobe-section">
                        <h4 class="znd-section-title"><i class="fa fa-star text-warning"></i> <?php esc_html_e('Icon đã sở hữu', 'zibll-nick-decor'); ?></h4>
                        <div class="znd-grid">
                            <?php foreach ($inv['icons'] as $id => $data):
                                if (!isset($icons[$id])) continue;
                                $item = $icons[$id];
                                $is_equipped = ($equipped_icon === $id);
                                $anim_class  = !empty($item['animation']) && $item['animation'] !== 'none' ? ' znd-anim-' . esc_attr($item['animation']) : '';
                                $expiry_text = !empty($data['expires_at']) ? sprintf(__('Hết hạn: %s', 'zibll-nick-decor'), date_i18n('d/m/Y', $data['expires_at'])) : __('Vĩnh viễn', 'zibll-nick-decor');
                            ?>
                            <div class="znd-card <?php echo $is_equipped ? 'is-equipped' : 'is-owned'; ?>">
                                <div class="znd-card-icon-preview">
                                    <span class="znd-nick-icon <?php echo $anim_class; ?>"><?php echo $item['svg']; ?></span>
                                </div>
                                <div class="znd-card-info">
                                    <h4 class="znd-card-title"><?php echo esc_html($item['name']); ?></h4>
                                    <span class="znd-tag znd-tag-duration"><i class="fa fa-shield"></i> <?php echo esc_html($expiry_text); ?></span>
                                </div>
                                <div class="znd-card-actions">
                                    <?php if ($is_equipped): ?>
                                        <button type="button" class="znd-btn znd-btn-unequip" data-action="unequip" data-type="icon">
                                            <i class="fa fa-times"></i> <?php esc_html_e('Tháo trang bị', 'zibll-nick-decor'); ?>
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="znd-btn znd-btn-equip" data-action="equip" data-type="icon" data-id="<?php echo esc_attr($id); ?>">
                                            <i class="fa fa-bolt"></i> <?php esc_html_e('Đeo icon này', 'zibll-nick-decor'); ?>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <h4 class="znd-section-title mt20"><i class="fa fa-magic text-primary"></i> <?php esc_html_e('Màu nick đã sở hữu', 'zibll-nick-decor'); ?></h4>
                        <div class="znd-grid">
                            <?php foreach ($inv['colors'] as $id => $data):
                                if (!isset($colors[$id])) continue;
                                $item = $colors[$id];
                                $is_equipped = ($equipped_color === $id);
                                $anim_class  = !empty($item['animation']) && $item['animation'] !== 'none' ? ' znd-anim-' . esc_attr($item['animation']) : '';
                                $expiry_text = !empty($data['expires_at']) ? sprintf(__('Hết hạn: %s', 'zibll-nick-decor'), date_i18n('d/m/Y', $data['expires_at'])) : __('Vĩnh viễn', 'zibll-nick-decor');
                            ?>
                            <div class="znd-card <?php echo $is_equipped ? 'is-equipped' : 'is-owned'; ?>">
                                <div class="znd-card-color-sample">
                                    <span class="znd-sample-text znd-color-<?php echo esc_attr($id); ?> <?php echo $anim_class; ?>"><?php echo esc_html($item['name']); ?></span>
                                </div>
                                <div class="znd-card-info">
                                    <h4 class="znd-card-title"><?php echo esc_html($item['name']); ?></h4>
                                    <span class="znd-tag znd-tag-duration"><i class="fa fa-shield"></i> <?php echo esc_html($expiry_text); ?></span>
                                </div>
                                <div class="znd-card-actions">
                                    <?php if ($is_equipped): ?>
                                        <button type="button" class="znd-btn znd-btn-unequip" data-action="unequip" data-type="color">
                                            <i class="fa fa-times"></i> <?php esc_html_e('Tháo màu nick', 'zibll-nick-decor'); ?>
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="znd-btn znd-btn-equip" data-action="equip" data-type="color" data-id="<?php echo esc_attr($id); ?>">
                                            <i class="fa fa-bolt"></i> <?php esc_html_e('Dùng màu này', 'zibll-nick-decor'); ?>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>
        <?php
        return ob_get_clean();
    }
}
