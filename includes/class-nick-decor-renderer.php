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

    /**
     * Chuẩn hoá và cô lập ID gradient trong thẻ SVG, đảm bảo mỗi instance có ID duy nhất
     * Tránh lỗi trùng lặp ID và lỗi WebKit/Blink SVG khi phần tử đầu tiên rơi vào display:none trên di động
     *
     * @param string $svg Chuỗi mã SVG
     * @return string
     */
    public static function prepare_svg($svg) {
        if (empty($svg) || !is_string($svg)) {
            return '';
        }

        static $seq = 0;
        $seq++;
        $uid = 'u' . $seq . '_' . wp_rand(100, 999);

        // Đổi tất cả id="znd_g_xxx" thành id="znd_g_xxx_{uid}"
        $svg = preg_replace('/(\bid=["\'])(znd_g_[^"\']+)(["\'])/i', '$1$2_' . $uid . '$3', $svg);

        // Đổi tất cả url(#znd_g_xxx) thành url(#znd_g_xxx_{uid})
        $svg = preg_replace('/(\burl\(#)(znd_g_[^)\s]+)(\))/i', '$1$2_' . $uid . '$3', $svg);

        return $svg;
    }

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

        // Can thiệp khối Author/User Header trong Trung tâm người dùng (/user)
        add_action('user_center_page_content', array($this, 'start_user_page_header_buffer'), 7);
        add_action('user_center_page_content', array($this, 'end_user_page_header_buffer'), 9);

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
        $equipped_icon   = ZND_User::get_equipped_icon($current_user_id);
        $equipped_color  = ZND_User::get_equipped_color($current_user_id);

        $user_icon_html   = '';
        $user_color_class = '';

        if (!empty($equipped_icon)) {
            $icon_data = ZND_Defaults::get_icon($equipped_icon);
            if ($icon_data && !empty($icon_data['svg'])) {
                $anim_class = !empty($icon_data['animation']) && $icon_data['animation'] !== 'none' ? ' znd-anim-' . esc_attr($icon_data['animation']) : '';
                $icon_name  = esc_attr($icon_data['name']);
                $svg        = self::prepare_svg($icon_data['svg']);
                $user_icon_html = '<span class="znd-nick-icon znd-icon-' . esc_attr($equipped_icon) . $anim_class . '" title="' . $icon_name . '">' . $svg . '</span>';
            }
        }

        if (!empty($equipped_color)) {
            $color_data = ZND_Defaults::get_color($equipped_color);
            if ($color_data) {
                $anim_class = !empty($color_data['animation']) && $color_data['animation'] !== 'none' ? ' znd-anim-' . esc_attr($color_data['animation']) : '';
                $user_color_class = 'znd-color-' . esc_attr($equipped_color) . $anim_class;
            }
        }

        wp_localize_script('znd-frontend', 'znd_vars', array(
            'ajax_url'                 => admin_url('admin-ajax.php'),
            'nonce'                    => wp_create_nonce('znd_nonce'),
            'is_logged_in'             => is_user_logged_in(),
            'user_id'                  => $current_user_id,
            'is_admin'                 => ZND_Defaults::is_user_admin($current_user_id),
            'is_mod'                   => ZND_Defaults::is_user_mod($current_user_id),
            'is_banned'                => ZND_Defaults::is_user_banned($current_user_id),
            'equipped_icon'            => $equipped_icon,
            'equipped_color'           => $equipped_color,
            'user_icon_html'           => $user_icon_html,
            'user_color_class'         => $user_color_class,
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

        if (ZND_Defaults::is_user_banned($user_id)) {
            $icon_id  = 'lock_banned';
            $color_id = 'color_banned';
        } else {
            $icon_id  = ZND_User::get_equipped_icon($user_id);
            $color_id = ZND_User::get_equipped_color($user_id);
        }

        if (empty($icon_id) && empty($color_id)) {
            return $html;
        }

        $icon_html = '';
        if (!empty($icon_id)) {
            $icon_data = ZND_Defaults::get_icon($icon_id);
            if ($icon_data && !empty($icon_data['svg'])) {
                $anim_class = !empty($icon_data['animation']) && $icon_data['animation'] !== 'none' ? ' znd-anim-' . esc_attr($icon_data['animation']) : '';
                $icon_name  = esc_attr($icon_data['name']);
                $svg        = self::prepare_svg($icon_data['svg']);
                $icon_html  = '<span class="znd-nick-icon znd-icon-' . esc_attr($icon_id) . $anim_class . '" title="' . $icon_name . '">' . $svg . '</span>';
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

        // Tìm thẻ <a class="display-name..." hoặc <span class="display-name..." để chèn icon và thêm class màu
        // Cấu trúc Zibll chuẩn: <a class="display-name text-ellipsis $class" href="$url">$vip_icon$display_name</a>
        if (preg_match('/(<(?:a|span|div)[^>]*class=["\'][^"\']*display-name[^"\']*["\'][^>]*>)(.*?)(<\/(?:a|span|div)>)/is', $html, $matches)) {
            $open_tag  = $matches[1];
            $inner_con = $matches[2];
            $close_tag = $matches[3];

            // Thêm class nhận diện có icon đầu nick
            if (!empty($icon_html)) {
                $open_tag = preg_replace('/class=["\']([^"\']*)["\']/', 'class="$1 has-znd-icon"', $open_tag);
            }

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

            // Gắn thêm has-znd-icon vào thẻ <name> nếu có icon
            if (!empty($icon_html) && strpos($html, '<name') !== false) {
                $html = preg_replace('/(<name[^>]*class=["\'][^"\']*)["\']/', '$1 has-znd-icon"', $html, 1);
            }
        }

        return $html;
    }

    /**
     * Bắt đầu buffer khối Author/User Header trong Trung tâm người dùng (/user)
     */
    public function start_user_page_header_buffer() {
        if (!self::is_enabled()) {
            return;
        }
        ob_start();
    }

    /**
     * Kết thúc buffer và chèn icon cùng màu nick vào Author/User Header trong Trung tâm người dùng (/user)
     */
    public function end_user_page_header_buffer() {
        if (!self::is_enabled()) {
            return;
        }
        $html = ob_get_clean();
        if (empty($html)) {
            return;
        }

        $user_id = get_current_user_id();
        if ($user_id) {
            $icon_id  = ZND_User::get_equipped_icon($user_id);
            $color_id = ZND_User::get_equipped_color($user_id);

            if (!empty($icon_id) || !empty($color_id)) {
                $icon_html   = '';
                $color_class = '';

                if (!empty($icon_id)) {
                    $icon_data = ZND_Defaults::get_icon($icon_id);
                    if ($icon_data && !empty($icon_data['svg'])) {
                        $anim_class = !empty($icon_data['animation']) && $icon_data['animation'] !== 'none' ? ' znd-anim-' . esc_attr($icon_data['animation']) : '';
                        $icon_name  = esc_attr($icon_data['name']);
                        $svg        = self::prepare_svg($icon_data['svg']);
                        $icon_html  = '<span class="znd-nick-icon znd-icon-' . esc_attr($icon_id) . $anim_class . '" title="' . $icon_name . '">' . $svg . '</span>';
                    }
                }

                if (!empty($color_id)) {
                    $color_data = ZND_Defaults::get_color($color_id);
                    if ($color_data) {
                        $anim_class  = !empty($color_data['animation']) && $color_data['animation'] !== 'none' ? ' znd-anim-' . esc_attr($color_data['animation']) : '';
                        $color_class = ' znd-color-' . esc_attr($color_id) . $anim_class;
                    }
                }

                // Tìm thẻ <span class="display-name"> hoặc <a class="display-name"> trong author-header
                if (preg_match('/(<(?:span|a|div)[^>]*class=["\'][^"\']*display-name[^"\']*["\'][^>]*>)(.*?)(<\/(?:span|a|div)>)/is', $html, $matches)) {
                    $open_tag  = $matches[1];
                    $inner_con = $matches[2];
                    $close_tag = $matches[3];

                    if (!empty($icon_html)) {
                        $open_tag = preg_replace('/class=["\']([^"\']*)["\']/', 'class="$1 has-znd-icon"', $open_tag);
                    }
                    if (!empty($color_class)) {
                        $open_tag = preg_replace('/class=["\']([^"\']*)["\']/', 'class="$1' . $color_class . '"', $open_tag);
                    }

                    if (!empty($icon_html)) {
                        if (preg_match('/(<span[^>]*class=["\'][^"\']*mr3[^"\']*["\'][^>]*>.*?<\/span>)/is', $inner_con, $vip_m)) {
                            $inner_con = str_replace($vip_m[1], $vip_m[1] . $icon_html, $inner_con);
                        } else {
                            $inner_con = $icon_html . $inner_con;
                        }
                    }

                    $new_tag = $open_tag . $inner_con . $close_tag;
                    $html = str_replace($matches[0], $new_tag, $html);
                }
            }
        }

        echo $html;
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
                $svg        = self::prepare_svg($icon_data['svg']);
                $icon_html  = '<span class="znd-nick-icon znd-icon-' . esc_attr($icon_id) . $anim_class . '" title="' . $icon_name . '">' . $svg . '</span>';
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

            if (!empty($icon_html)) {
                $open_tag = preg_replace('/class=["\']([^"\']*)["\']/', 'class="$1 has-znd-icon"', $open_tag);
            }

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

        // Chỉ hiển thị biểu tượng mở Shop khi người dùng đã đăng nhập
        if (!is_user_logged_in()) {
            return $radius_but;
        }

        $show_btn = function_exists('_pz') ? _pz('znd_enable_user_menu_btn', true) : true;
        if (!$show_btn) {
            return $radius_but;
        }

        $btn = '<a href="javascript:;" data-znd-action="modal" class="toggle-radius open-nick-decor-modal ml10" title="' . esc_attr__('Cửa hàng màu & Icon nick', 'zibll-nick-decor') . '"><i class="fa fa-paint-brush"></i></a>';

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
        <div class="modal fade" id="znd-shop-modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <?php if (function_exists('zib_get_modal_colorful_header')): ?>
                        <?php echo zib_get_modal_colorful_header('jb-yellow', '<i class="fa fa-paint-brush"></i>', '<div class="em12 font-bold">' . esc_html__('Cửa hàng màu & Icon nick', 'zibll-nick-decor') . '</div><div class="em09 opacity8 mt6 font-normal">' . esc_html__('Tùy biến phong cách nickname độc bản và nổi bật cho thành viên', 'zibll-nick-decor') . '</div>'); ?>
                    <?php else: ?>
                        <div class="box-body notop mb10 touch">
                            <button class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
                            <b class="modal-title flex ac"><span class="badg c-yellow mr6"><i class="fa fa-paint-brush"></i></span> <?php esc_html_e('Cửa hàng màu & Icon nick', 'zibll-nick-decor'); ?></b>
                        </div>
                    <?php endif; ?>
                    <div class="modal-body">
                        <?php echo self::render_shop_html($user_id, true); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Zibll Member Nickname & Badge Pro Confirm Modal (Modal xác nhận chuẩn Theme Zibll modal_signout) -->
        <div class="modal fade znd-confirm-modal-wrap" id="znd-confirm-modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div style="padding: 1px;">
                            <?php
                            $header_icon  = function_exists('zib_get_svg') ? zib_get_svg('shopping-cart') : '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-shopping-cart"></use></svg>';
                            $header_title = esc_html__('Xác nhận mua vật phẩm', 'zibll-nick-decor');
                            if (function_exists('zib_get_modal_colorful_header')):
                                echo zib_get_modal_colorful_header('jb-yellow', $header_icon, $header_title);
                            else: ?>
                                <div class="modal-colorful-header colorful-bg jb-yellow">
                                    <button class="close znd-confirm-cancel" data-dismiss="modal">
                                        <?php echo function_exists('zib_get_svg') ? zib_get_svg('close', null, 'ic-close') : '<i class="fa fa-times"></i>'; ?>
                                    </button>
                                    <div class="colorful-make"></div>
                                    <div class="text-center">
                                        <div class="em2x"><?php echo $header_icon; ?></div>
                                        <div class="mt10 em12 padding-w10 font-bold"><?php echo $header_title; ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div style="padding: 15px 15px 10px;">
                                <div class="znd-confirm-preview mb10" style="min-height: 48px; display: flex; align-items: center; justify-content: center;"></div>
                                <div class="em12 font-bold mb6 znd-confirm-title text-center" style="color: var(--focus-color, #ff6f06);"></div>
                                <div class="muted-2-color mb6 em09 text-center"><?php esc_html_e('Thời hạn sử dụng:', 'zibll-nick-decor'); ?> <b class="c-yellow znd-confirm-duration"></b></div>
                                <div class="muted-2-color em09 text-center"><?php esc_html_e('Giá thanh toán:', 'zibll-nick-decor'); ?> <b class="c-red em12 font-bold znd-confirm-price"></b></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-buts but-average">
                        <a type="button" class="but znd-confirm-cancel" href="javascript:;"><?php esc_html_e('Hủy bỏ', 'zibll-nick-decor'); ?></a>
                        <a type="button" class="but c-yellow znd-confirm-ok" href="javascript:;"><?php esc_html_e('Xác nhận mua', 'zibll-nick-decor'); ?></a>
                    </div>
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
            <div class="znd-live-preview-box muted-box padding-15 mb20 radius8">
                <div class="znd-preview-label c-yellow font-bold mb10">
                    <i class="fa fa-eye fa-fw mr6"></i><?php esc_html_e('Xem trước diện mạo của bạn:', 'zibll-nick-decor'); ?>
                </div>
                <div class="znd-preview-card flex ac padding-10 radius8">
                    <div class="znd-preview-avatar mr10"><?php echo $avatar; ?></div>
                    <div class="znd-preview-info flex1">
                        <div class="znd-preview-name-line font-bold em11">
                            <span id="znd-preview-icon" class="znd-nick-icon">
                                <?php
                                if (!empty($equipped_icon) && isset($icons[$equipped_icon])) {
                                    $i_data = $icons[$equipped_icon];
                                    $anim_class = !empty($i_data['animation']) && $i_data['animation'] !== 'none' ? ' znd-anim-' . esc_attr($i_data['animation']) : '';
                                    echo '<span class="' . $anim_class . '">' . self::prepare_svg($i_data['svg']) . '</span>';
                                }
                                ?>
                            </span>
                            <span id="znd-preview-name" class="znd-preview-display-name <?php echo !empty($equipped_color) ? 'znd-color-' . esc_attr($equipped_color) : ''; ?>">
                                <?php echo esc_html($user_name); ?>
                            </span>
                        </div>
                        <div class="znd-preview-meta muted-2-color em09 mt3">
                            <span id="znd-preview-desc"><?php esc_html_e('Nhấp vào bất kỳ màu hoặc icon nào bên dưới để thử ngay!', 'zibll-nick-decor'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- THANH THỐNG KÊ TÀI SẢN THÀNH VIÊN -->
                <?php if (is_user_logged_in()): ?>
                <div class="znd-balance-bar flex jsb ac mt10 pt10">
                    <div class="znd-balance-info em09">
                        <i class="fa fa-wallet fa-fw c-yellow mr6"></i>
                        <span><?php esc_html_e('Số dư hiện có:', 'zibll-nick-decor'); ?></span>
                        <strong id="znd-current-balance" class="c-yellow font-bold em11 ml3"><?php echo number_format($balance); ?></strong>
                        <span class="muted-color ml3"><?php echo esc_html($currency_name); ?></span>
                    </div>
                    <?php
                    $currency_type = function_exists('_pz') ? _pz('znd_currency', 'points') : 'points';
                    $charge_button = '';
                    $charge_text   = '<i class="fa fa-plus-circle mr3"></i>' . __('Nạp thêm', 'zibll-nick-decor');
                    $charge_class  = 'but but-sm c-yellow radius znd-btn-charge';

                    if ($currency_type === 'balance') {
                        if (function_exists('zibpay_get_balance_charge_link')) {
                            $charge_button = zibpay_get_balance_charge_link($charge_class, $charge_text);
                        }
                        if (empty($charge_button) && function_exists('zibpay_get_points_pay_link')) {
                            $charge_button = zibpay_get_points_pay_link($charge_class, $charge_text);
                        }
                        if (empty($charge_button) && function_exists('zib_get_user_center_url')) {
                            $charge_button = '<a href="' . esc_url(zib_get_user_center_url('balance')) . '" target="_blank" class="' . esc_attr($charge_class) . '">' . $charge_text . '</a>';
                        }
                    } else {
                        if (function_exists('zibpay_get_points_pay_link')) {
                            $charge_button = zibpay_get_points_pay_link($charge_class, $charge_text);
                        }
                        if (empty($charge_button) && function_exists('zibpay_get_balance_charge_link')) {
                            $charge_button = zibpay_get_balance_charge_link($charge_class, $charge_text);
                        }
                        if (empty($charge_button) && function_exists('zib_get_user_center_url')) {
                            $charge_button = '<a href="' . esc_url(zib_get_user_center_url('points')) . '" target="_blank" class="' . esc_attr($charge_class) . '">' . $charge_text . '</a>';
                        }
                    }

                    if (!empty($charge_button)):
                    ?>
                    <div class="znd-charge-btn">
                        <?php echo $charge_button; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="znd-login-notice muted-2-color em09 mt10 pt10">
                    <i class="fa fa-sign-in fa-fw mr6"></i><?php esc_html_e('Vui lòng', 'zibll-nick-decor'); ?>
                    <a href="javascript:;" class="signin-loader c-yellow font-bold"><?php esc_html_e('Đăng nhập', 'zibll-nick-decor'); ?></a>
                    <?php esc_html_e('để mua sắm và trang bị màu & icon cho nick của bạn!', 'zibll-nick-decor'); ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- THANH ĐIỀU HƯỚNG TABS (CHUẨN THEME ZIBLL TAB-NAV-THEME) -->
            <ul class="list-inline tab-nav-theme font-bold text-center znd-nav-tabs mb15">
                <li class="active" data-tab="icons">
                    <a href="javascript:;" class="znd-tab-btn">
                        <i class="fa fa-star mr4"></i><?php esc_html_e('Icon nick', 'zibll-nick-decor'); ?>
                        <span class="badg badg-sm c-yellow ml6"><?php echo count($icons); ?></span>
                    </a>
                </li>
                <li data-tab="colors">
                    <a href="javascript:;" class="znd-tab-btn">
                        <i class="fa fa-paint-brush mr4"></i><?php esc_html_e('Màu nick', 'zibll-nick-decor'); ?>
                        <span class="badg badg-sm c-yellow ml6"><?php echo count($colors); ?></span>
                    </a>
                </li>
                <?php if (is_user_logged_in()): ?>
                <li data-tab="wardrobe">
                    <a href="javascript:;" class="znd-tab-btn">
                        <i class="fa fa-archive mr4"></i><?php esc_html_e('Tủ đồ', 'zibll-nick-decor'); ?>
                        <span class="badg badg-sm c-yellow znd-owned-count ml6"><?php echo count($inv['icons']) + count($inv['colors']); ?></span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>

            <!-- NỘI DUNG TAB 1: SHOP ICON ĐẦU NICK -->
            <div id="znd-tab-icons" class="znd-tab-content active">
                <div class="znd-grid">
                    <?php foreach ($icons as $id => $item):
                        if (empty($item['status'])) continue;
                        $role_exclusive = !empty($item['role_exclusive']) ? $item['role_exclusive'] : '';
                        if ($role_exclusive === 'banned') continue; // Ẩn icon bị khóa khỏi Shop bán hàng
                        $is_owned      = isset($inv['icons'][$id]);
                        $is_equipped   = ($equipped_icon === $id);
                        $anim_class    = !empty($item['animation']) && $item['animation'] !== 'none' ? ' znd-anim-' . esc_attr($item['animation']) : '';
                        $base_price    = isset($item['price']) ? (float)$item['price'] : 0;
                        $duration_opts = ZND_Defaults::get_duration_options($base_price);
                        $default_pkg   = $duration_opts[7];
                        $price_text    = $default_pkg['price_text'];
                        $duration_text = $default_pkg['name'];
                        if ($role_exclusive === 'admin') {
                            $price_text    = __('Đặc quyền Quản trị viên', 'zibll-nick-decor');
                            $duration_text = __('Vĩnh viễn', 'zibll-nick-decor');
                        } elseif ($role_exclusive === 'mod') {
                            $price_text    = __('Đặc quyền Điều hành viên', 'zibll-nick-decor');
                            $duration_text = __('Vĩnh viễn', 'zibll-nick-decor');
                        }
                        $prepared_svg  = self::prepare_svg($item['svg']);
                    ?>
                    <div class="znd-card <?php echo $is_equipped ? 'is-equipped' : ($is_owned ? 'is-owned' : ''); ?>"
                         data-id="<?php echo esc_attr($id); ?>"
                         data-type="icon"
                         data-name="<?php echo esc_attr($item['name']); ?>"
                         data-desc="<?php echo esc_attr(!empty($item['desc']) ? $item['desc'] : ''); ?>"
                         data-base-price="<?php echo esc_attr($base_price); ?>"
                         data-prices="<?php echo esc_attr(wp_json_encode($duration_opts)); ?>"
                         data-price-text="<?php echo esc_attr($price_text); ?>"
                         data-duration="<?php echo esc_attr($duration_text); ?>"
                         data-status="<?php echo $is_equipped ? 'equipped' : ($is_owned ? 'owned' : 'available'); ?>"
                         data-anim="<?php echo esc_attr(!empty($item['animation']) ? $item['animation'] : ''); ?>"
                         data-exclusive="<?php echo esc_attr($role_exclusive); ?>"
                         data-item-svg="<?php echo esc_attr($prepared_svg); ?>"
                         title="<?php echo esc_attr($item['name']); ?>">

                        <?php if ($role_exclusive === 'admin'): ?>
                            <span class="znd-exclusive-badge badge-admin"><i class="fa fa-shield mr3"></i>Admin</span>
                        <?php elseif ($role_exclusive === 'mod'): ?>
                            <span class="znd-exclusive-badge badge-mod"><i class="fa fa-shield mr3"></i>Mod</span>
                        <?php endif; ?>

                        <div class="znd-card-icon-preview">
                            <span class="znd-nick-icon <?php echo $anim_class; ?>">
                                <?php echo $prepared_svg; ?>
                            </span>
                        </div>

                        <div class="znd-card-name"><?php echo esc_html($item['name']); ?></div>

                        <?php if ($is_equipped): ?>
                            <span class="znd-badge-status status-equipped" title="<?php esc_attr_e('Đang dùng', 'zibll-nick-decor'); ?>"><i class="fa fa-check"></i></span>
                        <?php elseif ($is_owned): ?>
                            <span class="znd-badge-status status-owned" title="<?php esc_attr_e('Đã sở hữu', 'zibll-nick-decor'); ?>"><i class="fa fa-circle"></i></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- NỘI DUNG TAB 2: SHOP MÀU SẮC NICK -->
            <div id="znd-tab-colors" class="znd-tab-content">
                <div class="znd-grid">
                    <?php foreach ($colors as $id => $item):
                        if (empty($item['status'])) continue;
                        $role_exclusive = !empty($item['role_exclusive']) ? $item['role_exclusive'] : '';
                        if ($role_exclusive === 'banned') continue; // Ẩn màu bị khóa khỏi Shop bán hàng
                        $is_owned      = isset($inv['colors'][$id]);
                        $is_equipped   = ($equipped_color === $id);
                        $anim_class    = !empty($item['animation']) && $item['animation'] !== 'none' ? ' znd-anim-' . esc_attr($item['animation']) : '';
                        $base_price    = isset($item['price']) ? (float)$item['price'] : 0;
                        $duration_opts = ZND_Defaults::get_duration_options($base_price);
                        $default_pkg   = $duration_opts[7];
                        $price_text    = $default_pkg['price_text'];
                        $duration_text = $default_pkg['name'];
                        if ($role_exclusive === 'admin') {
                            $price_text    = __('Đặc quyền Quản trị viên', 'zibll-nick-decor');
                            $duration_text = __('Vĩnh viễn', 'zibll-nick-decor');
                        } elseif ($role_exclusive === 'mod') {
                            $price_text    = __('Đặc quyền Điều hành viên', 'zibll-nick-decor');
                            $duration_text = __('Vĩnh viễn', 'zibll-nick-decor');
                        }
                    ?>
                    <div class="znd-card <?php echo $is_equipped ? 'is-equipped' : ($is_owned ? 'is-owned' : ''); ?>"
                         data-id="<?php echo esc_attr($id); ?>"
                         data-type="color"
                         data-name="<?php echo esc_attr($item['name']); ?>"
                         data-desc="<?php echo esc_attr(!empty($item['desc']) ? $item['desc'] : ''); ?>"
                         data-base-price="<?php echo esc_attr($base_price); ?>"
                         data-prices="<?php echo esc_attr(wp_json_encode($duration_opts)); ?>"
                         data-price-text="<?php echo esc_attr($price_text); ?>"
                         data-duration="<?php echo esc_attr($duration_text); ?>"
                         data-status="<?php echo $is_equipped ? 'equipped' : ($is_owned ? 'owned' : 'available'); ?>"
                         data-anim="<?php echo esc_attr(!empty($item['animation']) ? $item['animation'] : ''); ?>"
                         data-exclusive="<?php echo esc_attr($role_exclusive); ?>"
                         data-css="znd-color-<?php echo esc_attr($id); ?>"
                         title="<?php echo esc_attr($item['name']); ?>">

                        <?php if ($role_exclusive === 'admin'): ?>
                            <span class="znd-exclusive-badge badge-admin"><i class="fa fa-shield mr3"></i>Admin</span>
                        <?php elseif ($role_exclusive === 'mod'): ?>
                            <span class="znd-exclusive-badge badge-mod"><i class="fa fa-shield mr3"></i>Mod</span>
                        <?php endif; ?>

                        <div class="znd-card-color-sample">
                            <span class="znd-sample-text znd-color-<?php echo esc_attr($id); ?> <?php echo $anim_class; ?>">
                                Aa
                            </span>
                        </div>

                        <div class="znd-card-name"><?php echo esc_html($item['name']); ?></div>

                        <?php if ($is_equipped): ?>
                            <span class="znd-badge-status status-equipped" title="<?php esc_attr_e('Đang dùng', 'zibll-nick-decor'); ?>"><i class="fa fa-check"></i></span>
                        <?php elseif ($is_owned): ?>
                            <span class="znd-badge-status status-owned" title="<?php esc_attr_e('Đã sở hữu', 'zibll-nick-decor'); ?>"><i class="fa fa-circle"></i></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- NỘI DUNG TAB 3: TỦ ĐỒ CỦA TÔI -->
            <?php if (is_user_logged_in()): ?>
            <div id="znd-tab-wardrobe" class="znd-tab-content">
                <?php echo self::render_wardrobe_content($user_id); ?>
            </div>
            <?php endif; ?>

            <!-- THANH CHI TIẾT VẬT PHẨM & NÚT MUA / TRANG BỊ CỐ ĐỊNH Ở ĐÁY (ACTION DRAWER) -->
            <div class="znd-action-drawer" id="znd-action-drawer" style="display: none;">
                <div class="znd-drawer-content flex ac jsb">
                    <div class="znd-drawer-left flex ac">
                        <div class="znd-drawer-preview mr10" id="znd-drawer-preview"></div>
                        <div class="znd-drawer-info flex1">
                            <div class="znd-drawer-title-line flex ac">
                                <strong class="znd-drawer-name mr6" id="znd-drawer-name"></strong>
                                <span class="badg badg-sm c-yellow" id="znd-drawer-price"></span>
                                <span class="badg badg-sm ml3" id="znd-drawer-duration"></span>
                            </div>
                            <div class="znd-drawer-desc muted-color em09 mt3" id="znd-drawer-desc"></div>
                        </div>
                    </div>
                    <div class="znd-drawer-middle" id="znd-drawer-middle" style="display: none;">
                        <div class="znd-duration-pills" id="znd-drawer-packages"></div>
                    </div>
                    <div class="znd-drawer-right flex ac ml10">
                        <div class="znd-drawer-btn-wrap" id="znd-drawer-btn-wrap"></div>
                        <button type="button" class="znd-drawer-close ml10" id="znd-drawer-close" title="<?php esc_attr_e('Đóng chi tiết', 'zibll-nick-decor'); ?>">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render nội dung tab Tủ đồ của tôi (tái sử dụng khi tải trang và làm mới động qua AJAX)
     *
     * @param int $user_id
     * @return string
     */
    public static function render_wardrobe_content($user_id = 0) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        if (!$user_id) {
            return '';
        }

        $inv            = ZND_User::get_inventory($user_id);
        $equipped_icon  = ZND_User::get_equipped_icon($user_id);
        $equipped_color = ZND_User::get_equipped_color($user_id);
        $icons          = ZND_Defaults::get_all_icons();
        $colors         = ZND_Defaults::get_all_colors();

        ob_start();
        if (empty($inv['icons']) && empty($inv['colors'])) {
            ?>
            <div class="znd-empty-box text-center padding-30 muted-box radius8">
                <i class="fa fa-box-open fa-3x muted-3-color mb10"></i>
                <h4 class="font-bold"><?php esc_html_e('Tủ đồ của bạn đang trống!', 'zibll-nick-decor'); ?></h4>
                <p class="muted-color"><?php esc_html_e('Hãy chọn mua những icon và màu nick cực chất ở các tab bên cạnh để làm mới phong cách nhé.', 'zibll-nick-decor'); ?></p>
            </div>
            <?php
        } else {
            ?>
            <div class="znd-wardrobe-section">
                <?php if (!empty($inv['icons'])): ?>
                <h4 class="znd-section-title font-bold mb10"><i class="fa fa-star c-yellow mr6"></i><?php esc_html_e('Icon đã sở hữu', 'zibll-nick-decor'); ?></h4>
                <div class="znd-grid">
                    <?php foreach ($inv['icons'] as $id => $data):
                        if (!isset($icons[$id])) continue;
                        $item = $icons[$id];
                        $role_exclusive = !empty($data['role_exclusive']) ? $data['role_exclusive'] : (!empty($item['role_exclusive']) ? $item['role_exclusive'] : '');
                        $is_equipped = ($equipped_icon === $id);
                        $anim_class  = !empty($item['animation']) && $item['animation'] !== 'none' ? ' znd-anim-' . esc_attr($item['animation']) : '';
                        if ($role_exclusive === 'admin') {
                            $expiry_text = __('Đặc quyền Quản trị viên (Vĩnh viễn)', 'zibll-nick-decor');
                        } elseif ($role_exclusive === 'mod') {
                            $expiry_text = __('Đặc quyền Điều hành viên (Vĩnh viễn)', 'zibll-nick-decor');
                        } elseif ($role_exclusive === 'banned') {
                            $expiry_text = __('Tài khoản đang bị khóa', 'zibll-nick-decor');
                        } else {
                            $expiry_text  = !empty($data['expires_at']) ? sprintf(__('Hết hạn: %s', 'zibll-nick-decor'), date_i18n('d/m/Y', $data['expires_at'])) : __('Vĩnh viễn', 'zibll-nick-decor');
                        }
                        $prepared_svg = self::prepare_svg($item['svg']);
                    ?>
                    <div class="znd-card <?php echo $is_equipped ? 'is-equipped' : 'is-owned'; ?>"
                         data-id="<?php echo esc_attr($id); ?>"
                         data-type="icon"
                         data-name="<?php echo esc_attr($item['name']); ?>"
                         data-desc="<?php echo esc_attr(!empty($item['desc']) ? $item['desc'] : ''); ?>"
                         data-price-text="<?php esc_attr_e('Đã sở hữu', 'zibll-nick-decor'); ?>"
                         data-duration="<?php echo esc_attr($expiry_text); ?>"
                         data-status="<?php echo $is_equipped ? 'equipped' : 'owned'; ?>"
                         data-anim="<?php echo esc_attr(!empty($item['animation']) ? $item['animation'] : ''); ?>"
                         data-exclusive="<?php echo esc_attr($role_exclusive); ?>"
                         data-item-svg="<?php echo esc_attr($prepared_svg); ?>"
                         title="<?php echo esc_attr($item['name']); ?>">

                        <?php if ($role_exclusive === 'admin'): ?>
                            <span class="znd-exclusive-badge badge-admin"><i class="fa fa-shield mr3"></i>Admin</span>
                        <?php elseif ($role_exclusive === 'mod'): ?>
                            <span class="znd-exclusive-badge badge-mod"><i class="fa fa-shield mr3"></i>Mod</span>
                        <?php elseif ($role_exclusive === 'banned'): ?>
                            <span class="znd-exclusive-badge badge-banned"><i class="fa fa-lock mr3"></i>Khóa</span>
                        <?php endif; ?>

                        <div class="znd-card-icon-preview">
                            <span class="znd-nick-icon <?php echo $anim_class; ?>"><?php echo $prepared_svg; ?></span>
                        </div>
                        <div class="znd-card-name"><?php echo esc_html($item['name']); ?></div>

                        <?php if ($is_equipped): ?>
                            <span class="znd-badge-status status-equipped" title="<?php esc_attr_e('Đang dùng', 'zibll-nick-decor'); ?>"><i class="fa fa-check"></i></span>
                        <?php else: ?>
                            <span class="znd-badge-status status-owned" title="<?php esc_attr_e('Đã sở hữu', 'zibll-nick-decor'); ?>"><i class="fa fa-circle"></i></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($inv['colors'])): ?>
                <h4 class="znd-section-title font-bold mt20 mb10"><i class="fa fa-magic c-blue mr6"></i><?php esc_html_e('Màu nick đã sở hữu', 'zibll-nick-decor'); ?></h4>
                <div class="znd-grid">
                    <?php foreach ($inv['colors'] as $id => $data):
                        if (!isset($colors[$id])) continue;
                        $item = $colors[$id];
                        $role_exclusive = !empty($data['role_exclusive']) ? $data['role_exclusive'] : (!empty($item['role_exclusive']) ? $item['role_exclusive'] : '');
                        $is_equipped = ($equipped_color === $id);
                        $anim_class  = !empty($item['animation']) && $item['animation'] !== 'none' ? ' znd-anim-' . esc_attr($item['animation']) : '';
                        if ($role_exclusive === 'admin') {
                            $expiry_text = __('Đặc quyền Quản trị viên (Vĩnh viễn)', 'zibll-nick-decor');
                        } elseif ($role_exclusive === 'mod') {
                            $expiry_text = __('Đặc quyền Điều hành viên (Vĩnh viễn)', 'zibll-nick-decor');
                        } elseif ($role_exclusive === 'banned') {
                            $expiry_text = __('Tài khoản đang bị khóa', 'zibll-nick-decor');
                        } else {
                            $expiry_text = !empty($data['expires_at']) ? sprintf(__('Hết hạn: %s', 'zibll-nick-decor'), date_i18n('d/m/Y', $data['expires_at'])) : __('Vĩnh viễn', 'zibll-nick-decor');
                        }
                    ?>
                    <div class="znd-card <?php echo $is_equipped ? 'is-equipped' : 'is-owned'; ?>"
                         data-id="<?php echo esc_attr($id); ?>"
                         data-type="color"
                         data-name="<?php echo esc_attr($item['name']); ?>"
                         data-desc="<?php echo esc_attr(!empty($item['desc']) ? $item['desc'] : ''); ?>"
                         data-price-text="<?php esc_attr_e('Đã sở hữu', 'zibll-nick-decor'); ?>"
                         data-duration="<?php echo esc_attr($expiry_text); ?>"
                         data-status="<?php echo $is_equipped ? 'equipped' : 'owned'; ?>"
                         data-anim="<?php echo esc_attr(!empty($item['animation']) ? $item['animation'] : ''); ?>"
                         data-exclusive="<?php echo esc_attr($role_exclusive); ?>"
                         data-css="znd-color-<?php echo esc_attr($id); ?>"
                         title="<?php echo esc_attr($item['name']); ?>">

                        <?php if ($role_exclusive === 'admin'): ?>
                            <span class="znd-exclusive-badge badge-admin"><i class="fa fa-shield mr3"></i>Admin</span>
                        <?php elseif ($role_exclusive === 'mod'): ?>
                            <span class="znd-exclusive-badge badge-mod"><i class="fa fa-shield mr3"></i>Mod</span>
                        <?php elseif ($role_exclusive === 'banned'): ?>
                            <span class="znd-exclusive-badge badge-banned"><i class="fa fa-lock mr3"></i>Khóa</span>
                        <?php endif; ?>

                        <div class="znd-card-color-sample">
                            <span class="znd-sample-text znd-color-<?php echo esc_attr($id); ?> <?php echo $anim_class; ?>">Aa</span>
                        </div>
                        <div class="znd-card-name"><?php echo esc_html($item['name']); ?></div>

                        <?php if ($is_equipped): ?>
                            <span class="znd-badge-status status-equipped" title="<?php esc_attr_e('Đang dùng', 'zibll-nick-decor'); ?>"><i class="fa fa-check"></i></span>
                        <?php else: ?>
                            <span class="znd-badge-status status-owned" title="<?php esc_attr_e('Đã sở hữu', 'zibll-nick-decor'); ?>"><i class="fa fa-circle"></i></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php
        }
        return ob_get_clean();
    }
}
