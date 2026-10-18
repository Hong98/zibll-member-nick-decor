<?php
/**
 * Zibll Member Nickname & Badge Pro - Xử lý các yêu cầu AJAX
 *
 * @package Zibll_Member_Nick_Decor
 */

if (!defined('ABSPATH')) {
    exit;
}

class ZND_Ajax {

    public static function init() {
        // AJAX người dùng Frontend
        add_action('wp_ajax_znd_buy_item', array(__CLASS__, 'ajax_buy_item'));
        add_action('wp_ajax_nopriv_znd_buy_item', array(__CLASS__, 'ajax_need_login'));

        add_action('wp_ajax_znd_equip_item', array(__CLASS__, 'ajax_equip_item'));
        add_action('wp_ajax_nopriv_znd_equip_item', array(__CLASS__, 'ajax_need_login'));

        add_action('wp_ajax_znd_unequip_item', array(__CLASS__, 'ajax_unequip_item'));
        add_action('wp_ajax_nopriv_znd_unequip_item', array(__CLASS__, 'ajax_need_login'));

        // AJAX Admin quản lý thành viên
        add_action('wp_ajax_znd_admin_search_user', array(__CLASS__, 'ajax_admin_search_user'));
        add_action('wp_ajax_znd_admin_grant_item', array(__CLASS__, 'ajax_admin_grant_item'));
        add_action('wp_ajax_znd_admin_revoke_item', array(__CLASS__, 'ajax_admin_revoke_item'));
    }

    /**
     * Báo lỗi chưa đăng nhập
     */
    public static function ajax_need_login() {
        wp_send_json_error(array(
            'message' => __('Vui lòng đăng nhập để thực hiện chức năng này!', 'zibll-nick-decor'),
        ));
    }

    /**
     * Kiểm tra Nonce bảo mật (chống crash do cache HTML giữa guest và logged-in user)
     */
    private static function check_nonce() {
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
        if (!wp_verify_nonce($nonce, 'znd_nonce')) {
            if (!is_user_logged_in()) {
                wp_send_json_error(array(
                    'message' => __('Phiên làm việc đã hết hạn hoặc chưa đăng nhập. Vui lòng tải lại trang!', 'zibll-nick-decor'),
                ));
            }
        }
    }

    /**
     * Xử lý mua màu hoặc icon
     */
    public static function ajax_buy_item() {
        self::check_nonce();

        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error(array('message' => __('Vui lòng đăng nhập!', 'zibll-nick-decor')));
        }

        $type    = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        $item_id = isset($_POST['item_id']) ? sanitize_text_field($_POST['item_id']) : '';

        if (empty($type) || empty($item_id)) {
            wp_send_json_error(array('message' => __('Thông tin vật phẩm không hợp lệ!', 'zibll-nick-decor')));
        }

        $result = ZND_User::buy_item($user_id, $type, $item_id);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success($result);
    }

    /**
     * Xử lý trang bị màu hoặc icon
     */
    public static function ajax_equip_item() {
        self::check_nonce();

        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error(array('message' => __('Vui lòng đăng nhập!', 'zibll-nick-decor')));
        }

        $type    = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        $item_id = isset($_POST['item_id']) ? sanitize_text_field($_POST['item_id']) : '';

        if (empty($type) || empty($item_id)) {
            wp_send_json_error(array('message' => __('Thông tin không hợp lệ!', 'zibll-nick-decor')));
        }

        $result = ZND_User::equip_item($user_id, $type, $item_id);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success($result);
    }

    /**
     * Xử lý tháo màu hoặc icon
     */
    public static function ajax_unequip_item() {
        self::check_nonce();

        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error(array('message' => __('Vui lòng đăng nhập!', 'zibll-nick-decor')));
        }

        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        if (empty($type)) {
            wp_send_json_error(array('message' => __('Thông tin không hợp lệ!', 'zibll-nick-decor')));
        }

        $result = ZND_User::unequip_item($user_id, $type);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success($result);
    }

    /**
     * Admin: Tra cứu thông tin thành viên
     */
    public static function ajax_admin_search_user() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Bạn không có quyền thực hiện thao tác này!', 'zibll-nick-decor')));
        }

        $keyword = isset($_POST['keyword']) ? trim(sanitize_text_field($_POST['keyword'])) : '';
        if (empty($keyword)) {
            wp_send_json_error(array('message' => __('Vui lòng nhập ID hoặc Tên thành viên!', 'zibll-nick-decor')));
        }

        $user = null;
        if (is_numeric($keyword)) {
            $user = get_userdata((int)$keyword);
        } else {
            $user = get_user_by('login', $keyword);
            if (!$user) {
                $user = get_user_by('email', $keyword);
            }
        }

        if (!$user) {
            wp_send_json_error(array('message' => __('Không tìm thấy thành viên nào phù hợp!', 'zibll-nick-decor')));
        }

        $user_id = $user->ID;
        $inv = ZND_User::get_inventory($user_id);
        $equipped_icon = ZND_User::get_equipped_icon($user_id);
        $equipped_color = ZND_User::get_equipped_color($user_id);

        $all_icons = ZND_Defaults::get_all_icons();
        $all_colors = ZND_Defaults::get_all_colors();

        ob_start();
        ?>
        <div class="znd-admin-user-details" data-user-id="<?php echo esc_attr($user_id); ?>">
            <div style="display:flex;align-items:center;gap:15px;border-bottom:1px solid #e2e8f0;padding-bottom:15px;margin-bottom:15px;">
                <div style="font-size:36px;color:#3b82f6;"><i class="fa fa-user-circle"></i></div>
                <div>
                    <h3 style="margin:0;font-size:16px;color:#0f172a;"><?php echo esc_html($user->display_name); ?> <small style="color:#64748b;">(@<?php echo esc_html($user->user_login); ?> - ID: #<?php echo esc_html($user_id); ?>)</small></h3>
                    <p style="margin:4px 0 0 0;font-size:13px;color:#64748b;">
                        <?php echo esc_html($user->user_email); ?> |
                        <?php esc_html_e('Trang bị hiện tại:', 'zibll-nick-decor'); ?>
                        <strong><?php echo !empty($equipped_icon) && isset($all_icons[$equipped_icon]) ? esc_html($all_icons[$equipped_icon]['name']) : __('Không có', 'zibll-nick-decor'); ?></strong> (Icon),
                        <strong><?php echo !empty($equipped_color) && isset($all_colors[$equipped_color]) ? esc_html($all_colors[$equipped_color]['name']) : __('Không có', 'zibll-nick-decor'); ?></strong> (Màu)
                    </p>
                </div>
            </div>

            <!-- FORM CẤP TẶNG VẬT PHẨM -->
            <div style="background:#f1f5f9;border-radius:6px;padding:15px;margin-bottom:20px;">
                <h4 style="margin:0 0 10px 0;font-size:14px;color:#1e293b;"><i class="fa fa-gift text-primary"></i> <?php esc_html_e('Cấp tặng màu hoặc icon cho thành viên này', 'zibll-nick-decor'); ?></h4>
                <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                    <select id="znd_grant_type" class="form-control" style="max-width:140px;">
                        <option value="icon"><?php esc_html_e('Icon đầu nick', 'zibll-nick-decor'); ?></option>
                        <option value="color"><?php esc_html_e('Màu nick', 'zibll-nick-decor'); ?></option>
                    </select>

                    <select id="znd_grant_item_id" class="form-control" style="max-width:220px;">
                        <!-- Options sẽ cập nhật theo type -->
                        <?php foreach ($all_icons as $id => $item): ?>
                            <option value="<?php echo esc_attr($id); ?>" data-type="icon"><?php echo esc_html($item['name']); ?></option>
                        <?php endforeach; ?>
                        <?php foreach ($all_colors as $id => $item): ?>
                            <option value="<?php echo esc_attr($id); ?>" data-type="color" style="display:none;"><?php echo esc_html($item['name']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select id="znd_grant_duration" class="form-control" style="max-width:140px;">
                        <option value="0"><?php esc_html_e('Vĩnh viễn', 'zibll-nick-decor'); ?></option>
                        <option value="7"><?php esc_html_e('7 ngày', 'zibll-nick-decor'); ?></option>
                        <option value="30"><?php esc_html_e('30 ngày', 'zibll-nick-decor'); ?></option>
                        <option value="90"><?php esc_html_e('90 ngày', 'zibll-nick-decor'); ?></option>
                        <option value="365"><?php esc_html_e('1 năm', 'zibll-nick-decor'); ?></option>
                    </select>

                    <button type="button" id="znd_grant_submit_btn" class="button button-primary">
                        <i class="fa fa-plus-circle"></i> <?php esc_html_e('Cấp tặng ngay', 'zibll-nick-decor'); ?>
                    </button>
                </div>
            </div>

            <!-- DANH SÁCH VẬT PHẨM ĐANG SỞ HỮU -->
            <h4 style="margin:0 0 10px 0;font-size:14px;color:#1e293b;"><?php esc_html_e('Vật phẩm đang có trong tủ đồ:', 'zibll-nick-decor'); ?></h4>
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(260px, 1fr));gap:10px;">
                <?php
                $has_any = false;
                foreach ($inv['icons'] as $id => $data) {
                    $has_any = true;
                    $item = isset($all_icons[$id]) ? $all_icons[$id] : array('name' => $id);
                    $exp_text = !empty($data['expires_at']) ? sprintf(__('Hết hạn: %s', 'zibll-nick-decor'), date_i18n('d/m/Y', $data['expires_at'])) : __('Vĩnh viễn', 'zibll-nick-decor');
                    ?>
                    <div style="border:1px solid #e2e8f0;border-radius:6px;padding:10px;display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <strong><i class="fa fa-star text-warning"></i> <?php echo esc_html($item['name']); ?></strong>
                            <div style="font-size:12px;color:#64748b;"><?php echo esc_html($exp_text); ?></div>
                        </div>
                        <button type="button" class="button button-small button-link-delete znd-revoke-btn" data-type="icon" data-id="<?php echo esc_attr($id); ?>" style="color:#ef4444;">
                            <i class="fa fa-trash"></i> <?php esc_html_e('Thu hồi', 'zibll-nick-decor'); ?>
                        </button>
                    </div>
                    <?php
                }

                foreach ($inv['colors'] as $id => $data) {
                    $has_any = true;
                    $item = isset($all_colors[$id]) ? $all_colors[$id] : array('name' => $id);
                    $exp_text = !empty($data['expires_at']) ? sprintf(__('Hết hạn: %s', 'zibll-nick-decor'), date_i18n('d/m/Y', $data['expires_at'])) : __('Vĩnh viễn', 'zibll-nick-decor');
                    ?>
                    <div style="border:1px solid #e2e8f0;border-radius:6px;padding:10px;display:flex;justify-content:space-between;align-items:center;">
                        <div>
                            <strong><i class="fa fa-magic text-primary"></i> <?php echo esc_html($item['name']); ?></strong>
                            <div style="font-size:12px;color:#64748b;"><?php echo esc_html($exp_text); ?></div>
                        </div>
                        <button type="button" class="button button-small button-link-delete znd-revoke-btn" data-type="color" data-id="<?php echo esc_attr($id); ?>" style="color:#ef4444;">
                            <i class="fa fa-trash"></i> <?php esc_html_e('Thu hồi', 'zibll-nick-decor'); ?>
                        </button>
                    </div>
                    <?php
                }

                if (!$has_any) {
                    echo '<p style="color:#94a3b8;margin:0;">' . esc_html__('Thành viên này chưa sở hữu vật phẩm nào.', 'zibll-nick-decor') . '</p>';
                }
                ?>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        wp_send_json_success(array('html' => $html));
    }

    /**
     * Admin: Cấp tặng vật phẩm
     */
    public static function ajax_admin_grant_item() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Bạn không có quyền!', 'zibll-nick-decor')));
        }

        $user_id  = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
        $type     = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        $item_id  = isset($_POST['item_id']) ? sanitize_text_field($_POST['item_id']) : '';
        $duration = isset($_POST['duration']) ? (int)$_POST['duration'] : 0;

        if (!$user_id || empty($type) || empty($item_id)) {
            wp_send_json_error(array('message' => __('Dữ liệu không hợp lệ!', 'zibll-nick-decor')));
        }

        ZND_User::admin_grant_item($user_id, $type, $item_id, $duration);
        wp_send_json_success(array('message' => __('Đã cấp tặng vật phẩm thành công!', 'zibll-nick-decor')));
    }

    /**
     * Admin: Thu hồi vật phẩm
     */
    public static function ajax_admin_revoke_item() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Bạn không có quyền!', 'zibll-nick-decor')));
        }

        $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
        $type    = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        $item_id = isset($_POST['item_id']) ? sanitize_text_field($_POST['item_id']) : '';

        if (!$user_id || empty($type) || empty($item_id)) {
            wp_send_json_error(array('message' => __('Dữ liệu không hợp lệ!', 'zibll-nick-decor')));
        }

        ZND_User::admin_revoke_item($user_id, $type, $item_id);
        wp_send_json_success(array('message' => __('Đã thu hồi vật phẩm thành công!', 'zibll-nick-decor')));
    }
}
