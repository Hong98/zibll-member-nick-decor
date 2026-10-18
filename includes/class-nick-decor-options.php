<?php
/**
 * Zibll Member Nickname & Badge Pro - Cấu hình Codestar Framework (zibll_options)
 *
 * @package Zibll_Member_Nick_Decor
 */

if (!defined('ABSPATH')) {
    exit;
}

class ZND_Options {

    private static $registered = false;

    public static function init() {
        // Đăng ký các options vào Theme Zibll (Codestar Framework)
        add_action('after_setup_theme', array(__CLASS__, 'register_csf_sections'), 20);
        add_action('init', array(__CLASS__, 'register_csf_sections'), 20);

        // Định vị chính xác menu nằm ngay dưới "Dịch thuật JS" qua filter chính thức của Codestar Framework
        if (is_admin()) {
            add_filter('csf_zibll_options_sections', array(__CLASS__, 'filter_csf_sections'), 99);
            add_action('init', array(__CLASS__, 'reposition_menu_after_translate'), 990);
        }
    }

    /**
     * Tạo các section và field trong Codestar Framework
     */
    public static function register_csf_sections() {
        if (self::$registered) {
            return;
        }
        if (!class_exists('CSF')) {
            return;
        }

        self::$registered = true;
        $prefix = 'zibll_options';
        $parent_id = 'zibll_nick_decor_parent';

        // Lấy danh sách icon và màu mặc định dạng mảng cho group field
        $default_icons_array = array_values(ZND_Defaults::get_default_icons());
        $default_colors_array = array_values(ZND_Defaults::get_default_colors());

        // =========================================================================
        // MENU CHA: MÀU & ICON ĐẦU NICK (Tuân thủ sentence case theo quy tắc)
        // =========================================================================
        CSF::createSection($prefix, array(
            'id'    => $parent_id,
            'title' => __('Màu & Icon đầu nick', 'zibll-nick-decor'),
            'icon'  => 'fa fa-fw fa-paint-brush',
        ));

        // =========================================================================
        // MENU CON 1: CÀI ĐẶT CHUNG
        // =========================================================================
        CSF::createSection($prefix, array(
            'parent'      => $parent_id,
            'id'          => 'znd_sub_general',
            'title'       => __('Cài đặt chung', 'zibll-nick-decor'),
            'icon'        => 'fa fa-fw fa-sliders',
            'description' => __('Cấu hình bật tắt tính năng, hệ thống tiền tệ thanh toán và trải nghiệm người dùng.', 'zibll-nick-decor'),
            'fields'      => array(
                array(
                    'type'    => 'submessage',
                    'style'   => 'success',
                    'content' => '<h4 style="margin:0 0 6px 0;"><i class="fa fa-paint-brush fa-fw"></i> ' . __('Zibll Member Nickname & Badge Pro - Tùy biến màu sắc & Icon đầu nick', 'zibll-nick-decor') . '</h4>' .
                                 '<p style="margin:0;color:#666;">' . __('Hệ thống cửa hàng màu nick chuyển động và icon hiển thị đầu nickname thời thượng dành cho thành viên Theme Zibll. Tích hợp thanh toán bằng Điểm thưởng (Points) hoặc Số dư ví (Balance).', 'zibll-nick-decor') . '</p>',
                ),
                array(
                    'id'      => 'znd_enabled',
                    'type'    => 'switcher',
                    'title'   => __('Kích hoạt tính năng', 'zibll-nick-decor'),
                    'label'   => __('Bật hoặc tắt toàn bộ hệ thống màu nick và icon đầu nick trên toàn trang', 'zibll-nick-decor'),
                    'default' => true,
                ),
                array(
                    'id'         => 'znd_currency',
                    'type'       => 'radio',
                    'title'      => __('Đơn vị tiền tệ thanh toán', 'zibll-nick-decor'),
                    'desc'       => __('Chọn loại tiền tệ trong theme Zibll mà thành viên dùng để mua màu nick và icon.', 'zibll-nick-decor'),
                    'options'    => array(
                        'points'  => __('Điểm thưởng tích lũy (Points / Xu)', 'zibll-nick-decor'),
                        'balance' => __('Số dư ví tài khoản (Balance / Tiền)', 'zibll-nick-decor'),
                    ),
                    'default'    => 'points',
                    'inline'     => true,
                    'dependency' => array('znd_enabled', '==', 'true'),
                ),
                array(
                    'id'         => 'znd_currency_custom_name',
                    'type'       => 'text',
                    'title'      => __('Tên gọi tiền tệ hiển thị', 'zibll-nick-decor'),
                    'desc'       => __('Ví dụ: Điểm, Xu, Vàng, VNĐ... (Để trống sẽ tự động lấy theo cấu hình Theme Zibll).', 'zibll-nick-decor'),
                    'default'    => 'Điểm',
                    'dependency' => array('znd_enabled', '==', 'true'),
                ),
                array(
                    'id'         => 'znd_allow_roles',
                    'type'       => 'radio',
                    'title'      => __('Đối tượng được phép mua', 'zibll-nick-decor'),
                    'desc'       => __('Giới hạn quyền mua màu và icon cho các nhóm thành viên cụ thể.', 'zibll-nick-decor'),
                    'options'    => array(
                        'all'      => __('Tất cả thành viên đã đăng nhập', 'zibll-nick-decor'),
                        'vip_only' => __('Chỉ thành viên VIP (VIP 1, VIP 2...)', 'zibll-nick-decor'),
                    ),
                    'default'    => 'all',
                    'inline'     => true,
                    'dependency' => array('znd_enabled', '==', 'true'),
                ),
                array(
                    'id'         => 'znd_enable_animation',
                    'type'       => 'switcher',
                    'title'      => __('Hiệu ứng chuyển động (Animation)', 'zibll-nick-decor'),
                    'label'      => __('Bật hiệu ứng chuyển màu cầu vồng, phát sáng (glow), lấp lánh và bồng bềnh cho icon & màu nick', 'zibll-nick-decor'),
                    'default'    => true,
                    'dependency' => array('znd_enabled', '==', 'true'),
                ),
                array(
                    'id'         => 'znd_enable_author_tab',
                    'type'       => 'switcher',
                    'title'      => __('Tích hợp tab trong trang cá nhân', 'zibll-nick-decor'),
                    'label'      => __('Thêm tab "Màu & Icon" trực tiếp vào thanh điều hướng trang cá nhân (Author Page)', 'zibll-nick-decor'),
                    'default'    => true,
                    'dependency' => array('znd_enabled', '==', 'true'),
                ),
                array(
                    'id'         => 'znd_enable_user_menu_btn',
                    'type'       => 'switcher',
                    'title'      => __('Nút mở nhanh trong menu người dùng', 'zibll-nick-decor'),
                    'label'      => __('Hiển thị nút "Cửa hàng màu & Icon" trong menu thả xuống của tài khoản trên thanh Header', 'zibll-nick-decor'),
                    'default'    => true,
                    'dependency' => array('znd_enabled', '==', 'true'),
                ),
                array(
                    'id'         => 'znd_apply_locations',
                    'type'       => 'checkbox',
                    'title'      => __('Khu vực hiển thị màu & Icon', 'zibll-nick-decor'),
                    'desc'       => __('Chọn các vị trí sẽ hiển thị màu nick và icon đầu nick của thành viên.', 'zibll-nick-decor'),
                    'options'    => array(
                        'posts'    => __('Bài viết & Trang tác giả', 'zibll-nick-decor'),
                        'comments' => __('Khu vực bình luận', 'zibll-nick-decor'),
                        'bbs'      => __('Diễn đàn BBS (Bài đăng, chủ đề, bình luận)', 'zibll-nick-decor'),
                        'widgets'  => __('Các widget danh sách người dùng', 'zibll-nick-decor'),
                    ),
                    'default'    => array('posts', 'comments', 'bbs', 'widgets'),
                    'dependency' => array('znd_enabled', '==', 'true'),
                ),
            ),
        ));

        // =========================================================================
        // MENU CON 2: QUẢN LÝ ICON ĐẦU NICK
        // =========================================================================
        CSF::createSection($prefix, array(
            'parent'      => $parent_id,
            'id'          => 'znd_sub_icons',
            'title'       => __('Quản lý icon đầu nick', 'zibll-nick-decor'),
            'icon'        => 'fa fa-fw fa-star',
            'description' => __('Thêm mới, chỉnh sửa, xóa và tùy chỉnh danh mục các icon hiển thị đầu nickname.', 'zibll-nick-decor'),
            'fields'      => array(
                array(
                    'type'    => 'submessage',
                    'style'   => 'info',
                    'content' => '<strong><i class="fa fa-info-circle fa-fw"></i> ' . __('Bộ sưu tập icon tạo sẵn trong Shop', 'zibll-nick-decor') . '</strong>' .
                                 '<p style="margin:4px 0 0 0;color:#666;">' . __('Hệ thống đã tích hợp sẵn 16 mẫu icon SVG vector sắc nét, tự co giãn theo cỡ chữ. Bạn có thể thay đổi giá bán, thời hạn sử dụng hoặc thêm các icon tùy thích!', 'zibll-nick-decor') . '</p>',
                ),
                array(
                    'id'              => 'znd_icons_list',
                    'type'            => 'group',
                    'title'           => __('Danh sách icon trong shop', 'zibll-nick-decor'),
                    'button_title'    => __('Thêm icon mới', 'zibll-nick-decor'),
                    'accordion_title_by' => 'name',
                    'accordion_title_number' => true,
                    'default'         => $default_icons_array,
                    'fields'          => array(
                        array(
                            'id'      => 'status',
                            'type'    => 'switcher',
                            'title'   => __('Trạng thái mở bán', 'zibll-nick-decor'),
                            'default' => true,
                        ),
                        array(
                            'id'      => 'id',
                            'type'    => 'text',
                            'title'   => __('Mã định danh (ID)', 'zibll-nick-decor'),
                            'desc'    => __('Viết liền không dấu, duy nhất (ví dụ: crown_gold, diamond_blue)', 'zibll-nick-decor'),
                            'default' => '',
                        ),
                        array(
                            'id'      => 'name',
                            'type'    => 'text',
                            'title'   => __('Tên icon hiển thị', 'zibll-nick-decor'),
                            'desc'    => __('Tên icon xuất hiện trong shop (ví dụ: Vương miện hoàng gia)', 'zibll-nick-decor'),
                            'default' => '',
                        ),
                        array(
                            'id'      => 'desc',
                            'type'    => 'text',
                            'title'   => __('Mô tả ngắn', 'zibll-nick-decor'),
                            'desc'    => __('Mô tả ý nghĩa hoặc hiệu ứng của icon', 'zibll-nick-decor'),
                            'default' => '',
                        ),
                        array(
                            'id'      => 'price',
                            'type'    => 'number',
                            'title'   => __('Giá bán', 'zibll-nick-decor'),
                            'desc'    => __('Nhập 0 nếu muốn tặng miễn phí cho thành viên', 'zibll-nick-decor'),
                            'default' => 100,
                            'unit'    => 'xu/điểm',
                        ),
                        array(
                            'id'      => 'duration',
                            'type'    => 'select',
                            'title'   => __('Thời hạn sử dụng', 'zibll-nick-decor'),
                            'options' => array(
                                0   => __('Vĩnh viễn', 'zibll-nick-decor'),
                                7   => __('7 ngày', 'zibll-nick-decor'),
                                30  => __('30 ngày', 'zibll-nick-decor'),
                                90  => __('90 ngày', 'zibll-nick-decor'),
                                365 => __('1 năm (365 ngày)', 'zibll-nick-decor'),
                            ),
                            'default' => 0,
                        ),
                        array(
                            'id'      => 'animation',
                            'type'    => 'select',
                            'title'   => __('Hiệu ứng chuyển động (Animation)', 'zibll-nick-decor'),
                            'options' => array(
                                'none'      => __('Không chuyển động (Tĩnh)', 'zibll-nick-decor'),
                                'pulse'     => __('Nhấp nháy nhẹ (Pulse)', 'zibll-nick-decor'),
                                'glow'      => __('Phát sáng nhịp thở (Glow)', 'zibll-nick-decor'),
                                'float'     => __('Bồng bềnh êm ái (Float)', 'zibll-nick-decor'),
                                'shimmer'   => __('Lấp lánh ánh kim (Shimmer)', 'zibll-nick-decor'),
                                'spin-slow' => __('Xoay tròn chậm (Spin)', 'zibll-nick-decor'),
                            ),
                            'default' => 'shimmer',
                        ),
                        array(
                            'id'      => 'vip_level',
                            'type'    => 'select',
                            'title'   => __('Yêu cầu cấp VIP', 'zibll-nick-decor'),
                            'options' => array(
                                0 => __('Không yêu cầu (Tất cả)', 'zibll-nick-decor'),
                                1 => __('Yêu cầu VIP 1 trở lên', 'zibll-nick-decor'),
                                2 => __('Yêu cầu VIP 2 trở lên', 'zibll-nick-decor'),
                            ),
                            'default' => 0,
                        ),
                        array(
                            'id'      => 'svg',
                            'type'    => 'textarea',
                            'title'   => __('Mã SVG vector của icon', 'zibll-nick-decor'),
                            'desc'    => __('Dán toàn bộ mã &lt;svg ...&gt;...&lt;/svg&gt; của icon vào đây. Icon sẽ tự động hiển thị sắc nét ở mọi độ phân giải.', 'zibll-nick-decor'),
                            'default' => '',
                            'attributes' => array(
                                'rows' => 4,
                            ),
                        ),
                    ),
                ),
            ),
        ));

        // =========================================================================
        // MENU CON 3: QUẢN LÝ MÀU NICK
        // =========================================================================
        CSF::createSection($prefix, array(
            'parent'      => $parent_id,
            'id'          => 'znd_sub_colors',
            'title'       => __('Quản lý màu nick', 'zibll-nick-decor'),
            'icon'        => 'fa fa-fw fa-magic',
            'description' => __('Thêm mới, chỉnh sửa và cấu hình các dải màu Gradient, màu đơn và hiệu ứng chữ.', 'zibll-nick-decor'),
            'fields'      => array(
                array(
                    'type'    => 'submessage',
                    'style'   => 'info',
                    'content' => '<strong><i class="fa fa-info-circle fa-fw"></i> ' . __('Bộ sưu tập màu nick Gradient & Glow tạo sẵn', 'zibll-nick-decor') . '</strong>' .
                                 '<p style="margin:4px 0 0 0;color:#666;">' . __('Hệ thống đã chuẩn bị sẵn 12 bảng màu cao cấp (Cầu vồng động, Hoàng gia kim sắc, Cyber Neon, Xanh lam ngọc...). Tương thích hoàn hảo với cả nền sáng và nền tối (Dark mode) của Zibll.', 'zibll-nick-decor') . '</p>',
                ),
                array(
                    'id'              => 'znd_colors_list',
                    'type'            => 'group',
                    'title'           => __('Danh sách màu nick trong shop', 'zibll-nick-decor'),
                    'button_title'    => __('Thêm màu mới', 'zibll-nick-decor'),
                    'accordion_title_by' => 'name',
                    'accordion_title_number' => true,
                    'default'         => $default_colors_array,
                    'fields'          => array(
                        array(
                            'id'      => 'status',
                            'type'    => 'switcher',
                            'title'   => __('Trạng thái mở bán', 'zibll-nick-decor'),
                            'default' => true,
                        ),
                        array(
                            'id'      => 'id',
                            'type'    => 'text',
                            'title'   => __('Mã định danh (ID)', 'zibll-nick-decor'),
                            'desc'    => __('Viết liền không dấu, duy nhất (ví dụ: color_rainbow, color_gold)', 'zibll-nick-decor'),
                            'default' => '',
                        ),
                        array(
                            'id'      => 'name',
                            'type'    => 'text',
                            'title'   => __('Tên màu hiển thị', 'zibll-nick-decor'),
                            'desc'    => __('Tên màu hiển thị trong shop (ví dụ: Cầu vồng rực rỡ)', 'zibll-nick-decor'),
                            'default' => '',
                        ),
                        array(
                            'id'      => 'desc',
                            'type'    => 'text',
                            'title'   => __('Mô tả ngắn', 'zibll-nick-decor'),
                            'desc'    => __('Mô tả hiệu ứng màu sắc', 'zibll-nick-decor'),
                            'default' => '',
                        ),
                        array(
                            'id'      => 'price',
                            'type'    => 'number',
                            'title'   => __('Giá bán', 'zibll-nick-decor'),
                            'desc'    => __('Nhập 0 nếu muốn tặng miễn phí cho thành viên', 'zibll-nick-decor'),
                            'default' => 150,
                            'unit'    => 'xu/điểm',
                        ),
                        array(
                            'id'      => 'duration',
                            'type'    => 'select',
                            'title'   => __('Thời hạn sử dụng', 'zibll-nick-decor'),
                            'options' => array(
                                0   => __('Vĩnh viễn', 'zibll-nick-decor'),
                                7   => __('7 ngày', 'zibll-nick-decor'),
                                30  => __('30 ngày', 'zibll-nick-decor'),
                                90  => __('90 ngày', 'zibll-nick-decor'),
                                365 => __('1 năm (365 ngày)', 'zibll-nick-decor'),
                            ),
                            'default' => 0,
                        ),
                        array(
                            'id'      => 'animation',
                            'type'    => 'select',
                            'title'   => __('Hiệu ứng động (Animation)', 'zibll-nick-decor'),
                            'options' => array(
                                'none'    => __('Tĩnh (Không chuyển động)', 'zibll-nick-decor'),
                                'rainbow' => __('Dải cầu vồng trượt liên tục (Rainbow)', 'zibll-nick-decor'),
                                'shimmer' => __('Ánh kim quét lấp lánh (Shimmer)', 'zibll-nick-decor'),
                                'glow'    => __('Phát sáng nhịp thở (Glow)', 'zibll-nick-decor'),
                                'pulse'   => __('Nhịp đập năng lượng (Pulse)', 'zibll-nick-decor'),
                            ),
                            'default' => 'none',
                        ),
                        array(
                            'id'      => 'vip_level',
                            'type'    => 'select',
                            'title'   => __('Yêu cầu cấp VIP', 'zibll-nick-decor'),
                            'options' => array(
                                0 => __('Không yêu cầu (Tất cả)', 'zibll-nick-decor'),
                                1 => __('Yêu cầu VIP 1 trở lên', 'zibll-nick-decor'),
                                2 => __('Yêu cầu VIP 2 trở lên', 'zibll-nick-decor'),
                            ),
                            'default' => 0,
                        ),
                        array(
                            'id'      => 'css_style',
                            'type'    => 'textarea',
                            'title'   => __('Đoạn mã CSS áp dụng cho chữ', 'zibll-nick-decor'),
                            'desc'    => __('Mã CSS áp dụng lên tên hiển thị. Hỗ trợ linear-gradient, text-shadow, background-clip: text...', 'zibll-nick-decor'),
                            'default' => 'background: linear-gradient(135deg, #FFE259 0%, #FFA751 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;',
                            'attributes' => array(
                                'rows' => 3,
                            ),
                        ),
                    ),
                ),
            ),
        ));

        // =========================================================================
        // MENU CON 4: TỦ ĐỒ & THÀNH VIÊN
        // =========================================================================
        CSF::createSection($prefix, array(
            'parent'      => $parent_id,
            'id'          => 'znd_sub_users',
            'title'       => __('Tủ đồ & Thành viên', 'zibll-nick-decor'),
            'icon'        => 'fa fa-fw fa-users',
            'description' => __('Tra cứu thông tin trang bị của thành viên, cấp tặng hoặc thu hồi màu và icon.', 'zibll-nick-decor'),
            'fields'      => array(
                array(
                    'type'    => 'content',
                    'content' => self::render_admin_user_manager_ui(),
                ),
            ),
        ));

        // =========================================================================
        // MENU CON 5: TÙY BIẾN CSS & HƯỚNG DẪN
        // =========================================================================
        CSF::createSection($prefix, array(
            'parent'      => $parent_id,
            'id'          => 'znd_sub_custom_css',
            'title'       => __('Tùy biến CSS & Hướng dẫn', 'zibll-nick-decor'),
            'icon'        => 'fa fa-fw fa-code',
            'description' => __('Nhập mã CSS tùy chỉnh bổ sung và xem tài liệu hướng dẫn sử dụng.', 'zibll-nick-decor'),
            'fields'      => array(
                array(
                    'type'    => 'submessage',
                    'style'   => 'info',
                    'content' => '<h4 style="margin:0 0 6px 0;"><i class="fa fa-book fa-fw"></i> ' . __('Hướng dẫn sử dụng nhanh', 'zibll-nick-decor') . '</h4>' .
                                 '<ul style="margin:0;padding-left:18px;line-height:1.7;color:#555;">' .
                                 '<li><strong>Shortcode nhúng Shop vào bài viết/trang:</strong> <code>[zibll_nick_shop]</code></li>' .
                                 '<li><strong>Mở Modal Shop bằng nút bấm hoặc liên kết bất kỳ:</strong> thêm class <code>open-nick-decor-modal</code> hoặc thuộc tính <code>data-action="zibll_nick_decor_modal"</code> vào thẻ &lt;a&gt; hoặc &lt;button&gt;.</li>' .
                                 '<li><strong>Xem trước thời gian thực:</strong> Thành viên nhấp vào bất kỳ item nào trong Shop để xem trước ngay trên đầu modal trước khi quyết định mua.</li>' .
                                 '<li><strong>Bảo vệ đóng modal khi kéo chuột:</strong> Plugin tự động tuân thủ cơ chế kiểm tra mousedown & mouseup trên backdrop, tránh mất thao tác người dùng.</li>' .
                                 '</ul>',
                ),
                array(
                    'id'       => 'znd_custom_css',
                    'type'     => 'code_editor',
                    'title'    => __('CSS tùy chỉnh cho màu & Icon', 'zibll-nick-decor'),
                    'desc'     => __('Nhập mã CSS riêng để tùy biến giao diện Shop, Modal hoặc hiệu ứng theo phong cách website của bạn.', 'zibll-nick-decor'),
                    'settings' => array(
                        'theme' => 'dracula',
                        'mode'  => 'css',
                    ),
                    'default'  => '',
                ),
            ),
        ));
    }

    /**
     * Sắp xếp lại vị trí menu qua bộ lọc native của Codestar Framework
     */
    public static function filter_csf_sections($sections) {
        if (!is_array($sections) || empty($sections)) {
            return $sections;
        }

        $our_parent_id    = 'zibll_nick_decor_parent';
        $target_parent_id = 'zibll_translate_section';

        $our_sections   = array();
        $other_sections = array();

        foreach ($sections as $sec) {
            $sec_id    = isset($sec['id']) ? $sec['id'] : '';
            $parent_id = isset($sec['parent']) ? $sec['parent'] : '';

            if ($sec_id === $our_parent_id || $parent_id === $our_parent_id) {
                $our_sections[] = $sec;
            } else {
                $other_sections[] = $sec;
            }
        }

        if (empty($our_sections)) {
            return $sections;
        }

        // Tìm vị trí cuối cùng của nhóm Dịch thuật JS
        $insert_index = -1;
        foreach ($other_sections as $index => $sec) {
            $sec_id    = isset($sec['id']) ? $sec['id'] : '';
            $parent_id = isset($sec['parent']) ? $sec['parent'] : '';

            if ($sec_id === $target_parent_id || $parent_id === $target_parent_id) {
                $insert_index = $index;
            }
        }

        if ($insert_index !== -1) {
            array_splice($other_sections, $insert_index + 1, 0, $our_sections);
            return $other_sections;
        }

        return $sections;
    }

    /**
     * Tự động sắp xếp vị trí: Đưa menu "Màu & Icon đầu nick" nằm NGAY BÊN DƯỚI menu "Dịch thuật JS"
     */
    public static function reposition_menu_after_translate() {
        if (!is_admin() || !class_exists('CSF') || empty(CSF::$args['sections']['zibll_options'])) {
            return;
        }

        $sections = &CSF::$args['sections']['zibll_options'];
        $our_ids = array(
            'zibll_nick_decor_parent',
            'znd_sub_general',
            'znd_sub_icons',
            'znd_sub_colors',
            'znd_sub_users',
            'znd_sub_custom_css'
        );

        $our_sections   = array();
        $other_sections = array();

        foreach ($sections as $sec) {
            $sec_id    = isset($sec['id']) ? $sec['id'] : '';
            $parent_id = isset($sec['parent']) ? $sec['parent'] : '';
            if (in_array($sec_id, $our_ids, true) || in_array($parent_id, $our_ids, true)) {
                $our_sections[] = $sec;
            } else {
                $other_sections[] = $sec;
            }
        }

        if (empty($our_sections)) {
            return;
        }

        // Tìm vị trí cuối cùng của nhóm Dịch thuật JS ('zibll_translate_section')
        $insert_index = -1;
        foreach ($other_sections as $index => $sec) {
            $sec_id    = isset($sec['id']) ? $sec['id'] : '';
            $parent_id = isset($sec['parent']) ? $sec['parent'] : '';
            if ($sec_id === 'zibll_translate_section' || $parent_id === 'zibll_translate_section') {
                $insert_index = $index;
            }
        }

        if ($insert_index !== -1) {
            // Chèn ngay sau vị trí cuối cùng của nhóm Dịch thuật JS
            array_splice($other_sections, $insert_index + 1, 0, $our_sections);
            $sections = $other_sections;
        }
    }

    /**
     * Render giao diện HTML tra cứu và quản lý thành viên trong trang Admin CSF
     */
    private static function render_admin_user_manager_ui() {
        ob_start();
        ?>
        <div class="znd-admin-user-manager">
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:20px;margin-bottom:20px;">
                <h4 style="margin:0 0 12px 0;font-size:15px;color:#1e293b;">
                    <i class="fa fa-search fa-fw text-primary"></i> <?php esc_html_e('Tra cứu & Quản lý trang bị thành viên', 'zibll-nick-decor'); ?>
                </h4>
                <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                    <input type="text" id="znd_search_user_input" class="form-control" style="max-width:320px;" placeholder="<?php esc_attr_e('Nhập User ID hoặc Tên đăng nhập...', 'zibll-nick-decor'); ?>">
                    <button type="button" id="znd_search_user_btn" class="button button-primary">
                        <i class="fa fa-search fa-fw"></i> <?php esc_html_e('Tra cứu ngay', 'zibll-nick-decor'); ?>
                    </button>
                    <span id="znd_search_loading" style="display:none;color:#64748b;"><i class="fa fa-spinner fa-spin fa-fw"></i> <?php esc_html_e('Đang tìm...', 'zibll-nick-decor'); ?></span>
                </div>
            </div>

            <div id="znd_user_result_box" style="display:none;background:#fff;border:1px solid #cbd5e1;border-radius:8px;padding:20px;">
                <!-- Nội dung tra cứu thành viên sẽ được nạp qua AJAX -->
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
