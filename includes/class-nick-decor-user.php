<?php
/**
 * Zibll Member Nickname & Badge Pro - Xử lý dữ liệu Tủ đồ, Mua sắm và Trang bị của thành viên
 *
 * @package Zibll_Member_Nick_Decor
 */

if (!defined('ABSPATH')) {
    exit;
}

class ZND_User {

    const META_INVENTORY      = 'znd_user_inventory';
    const META_EQUIPPED_ICON  = 'znd_equipped_icon';
    const META_EQUIPPED_COLOR = 'znd_equipped_color';

    /**
     * Lấy toàn bộ tủ đồ của thành viên
     */
    public static function get_inventory($user_id = 0) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        if (!$user_id) {
            return array('icons' => array(), 'colors' => array());
        }

        $inv = get_user_meta($user_id, self::META_INVENTORY, true);
        if (!is_array($inv)) {
            $inv = array('icons' => array(), 'colors' => array());
        }

        if (!isset($inv['icons']) || !is_array($inv['icons'])) {
            $inv['icons'] = array();
        }
        if (!isset($inv['colors']) || !is_array($inv['colors'])) {
            $inv['colors'] = array();
        }

        // Tự động kiểm tra thời hạn
        $now = time();
        $changed = false;

        foreach (array('icons', 'colors') as $type) {
            foreach ($inv[$type] as $item_id => $data) {
                if (!empty($data['expires_at']) && $data['expires_at'] <= $now) {
                    unset($inv[$type][$item_id]);
                    $changed = true;

                    // Nếu đang trang bị item này thì tự động tháo
                    $equipped = ($type === 'icons') ? self::get_equipped_icon($user_id) : self::get_equipped_color($user_id);
                    if ($equipped === $item_id) {
                        self::unequip_item($user_id, $type);
                    }
                }
            }
        }

        if ($changed) {
            update_user_meta($user_id, self::META_INVENTORY, $inv);
        }

        return $inv;
    }

    /**
     * Kiểm tra thành viên có sở hữu một item cụ thể không
     */
    public static function has_item($user_id, $type, $item_id) {
        if (!$user_id || empty($item_id)) {
            return false;
        }

        // Quản trị viên (Super Admin) luôn có toàn quyền dùng thử hoặc trang bị
        if (user_can($user_id, 'manage_options')) {
            return true;
        }

        $inv = self::get_inventory($user_id);
        $bucket = ($type === 'icon' || $type === 'icons') ? 'icons' : 'colors';

        return isset($inv[$bucket][$item_id]);
    }

    /**
     * Lấy ID icon đang trang bị của thành viên
     */
    public static function get_equipped_icon($user_id = 0) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        if (!$user_id) {
            return '';
        }

        $icon_id = get_user_meta($user_id, self::META_EQUIPPED_ICON, true);
        if (empty($icon_id)) {
            return '';
        }

        // Kiểm tra quyền sở hữu
        if (!self::has_item($user_id, 'icon', $icon_id)) {
            delete_user_meta($user_id, self::META_EQUIPPED_ICON);
            return '';
        }

        return $icon_id;
    }

    /**
     * Lấy ID màu đang trang bị của thành viên
     */
    public static function get_equipped_color($user_id = 0) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        if (!$user_id) {
            return '';
        }

        $color_id = get_user_meta($user_id, self::META_EQUIPPED_COLOR, true);
        if (empty($color_id)) {
            return '';
        }

        // Kiểm tra quyền sở hữu
        if (!self::has_item($user_id, 'color', $color_id)) {
            delete_user_meta($user_id, self::META_EQUIPPED_COLOR);
            return '';
        }

        return $color_id;
    }

    /**
     * Lấy số dư hiện tại của thành viên theo loại tiền tệ đang chọn
     */
    public static function get_user_currency_balance($user_id = 0) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        if (!$user_id) {
            return 0;
        }

        $currency = function_exists('_pz') ? _pz('znd_currency', 'points') : 'points';

        if ($currency === 'balance') {
            return function_exists('zibpay_get_user_balance') ? zibpay_get_user_balance($user_id) : 0;
        } else {
            return function_exists('zibpay_get_user_points') ? zibpay_get_user_points($user_id) : 0;
        }
    }

    /**
     * Lấy tên tiền tệ hiển thị
     */
    public static function get_currency_name() {
        $currency = function_exists('_pz') ? _pz('znd_currency', 'points') : 'points';
        $custom_name = function_exists('_pz') ? _pz('znd_currency_custom_name', '') : '';

        if (!empty($custom_name)) {
            return esc_html($custom_name);
        }

        if ($currency === 'balance') {
            return __('Số dư ví (VNĐ)', 'zibll-nick-decor');
        }

        return __('Điểm', 'zibll-nick-decor');
    }

    /**
     * Xử lý mua một item (Màu hoặc Icon) với tùy chọn gói thời hạn (7 ngày, 30 ngày, Vĩnh viễn)
     */
    public static function buy_item($user_id, $type, $item_id, $duration = 7) {
        if (!$user_id) {
            return new WP_Error('not_logged_in', __('Bạn vui lòng đăng nhập để thực hiện mua sắm!', 'zibll-nick-decor'));
        }

        $type_normalized = ($type === 'icon' || $type === 'icons') ? 'icon' : 'color';
        $item = ($type_normalized === 'icon') ? ZND_Defaults::get_icon($item_id) : ZND_Defaults::get_color($item_id);

        if (!$item) {
            return new WP_Error('item_not_found', __('Vật phẩm không tồn tại hoặc đã ngừng mở bán!', 'zibll-nick-decor'));
        }

        if (empty($item['status'])) {
            return new WP_Error('item_disabled', __('Vật phẩm hiện đang tạm khóa!', 'zibll-nick-decor'));
        }

        // Kiểm tra quyền mua theo cấp VIP
        if (!empty($item['vip_level']) && function_exists('zib_get_user_vip_level')) {
            $user_vip = zib_get_user_vip_level($user_id);
            if ($user_vip < (int)$item['vip_level']) {
                return new WP_Error('vip_required', sprintf(__('Vật phẩm này yêu cầu cấp bậc từ VIP %d trở lên!', 'zibll-nick-decor'), (int)$item['vip_level']));
            }
        }

        // Kiểm tra xem đã sở hữu chưa (nếu là vĩnh viễn)
        $inv = self::get_inventory($user_id);
        $bucket = ($type_normalized === 'icon') ? 'icons' : 'colors';
        if (isset($inv[$bucket][$item_id])) {
            $existing = $inv[$bucket][$item_id];
            if (empty($existing['expires_at'])) {
                return new WP_Error('already_owned', __('Bạn đã sở hữu vật phẩm vĩnh viễn này rồi!', 'zibll-nick-decor'));
            }
        }

        // Chuẩn hóa thời hạn được chọn: 7, 30 hoặc 0 (vĩnh viễn)
        $duration_days = (int)$duration;
        if (!in_array($duration_days, array(7, 30, 0), true)) {
            $duration_days = 7;
        }

        $base_price    = isset($item['price']) ? (float)$item['price'] : 0;
        $price         = ZND_Defaults::get_item_price_by_duration($base_price, $duration_days);
        $currency      = function_exists('_pz') ? _pz('znd_currency', 'points') : 'points';
        $currency_name = self::get_currency_name();
        $duration_name = ($duration_days > 0) ? sprintf(__('%d ngày', 'zibll-nick-decor'), $duration_days) : __('Vĩnh viễn', 'zibll-nick-decor');

        // Kiểm tra số dư và trừ tiền
        if ($price > 0) {
            $user_balance = self::get_user_currency_balance($user_id);
            if ($user_balance < $price) {
                return new WP_Error('insufficient_funds', sprintf(__('Số dư của bạn không đủ! Cần có %s %s để mua gói %s của vật phẩm này.', 'zibll-nick-decor'), number_format($price), $currency_name, $duration_name));
            }

            // Tiến hành trừ tiền
            if ($currency === 'balance') {
                if (function_exists('zibpay_update_user_balance')) {
                    $order_num = 'ZND' . current_time('ymdHis') . mt_rand(100, 999);
                    $deduct = zibpay_update_user_balance($user_id, array(
                        'order_num' => $order_num,
                        'value'     => -$price,
                        'type'      => sprintf(__('Mua %s: %s (Gói %s)', 'zibll-nick-decor'), ($type_normalized === 'icon' ? 'Icon nick' : 'Màu nick'), $item['name'], $duration_name),
                        'desc'      => sprintf(__('Thanh toán trang bị %s thời hạn %s', 'zibll-nick-decor'), $item['name'], $duration_name),
                    ));
                    if (!$deduct) {
                        return new WP_Error('payment_failed', __('Giao dịch trừ số dư ví thất bại, vui lòng thử lại sau!', 'zibll-nick-decor'));
                    }
                }
            } else {
                if (function_exists('zibpay_update_user_points')) {
                    $order_num = 'ZND' . current_time('ymdHis') . mt_rand(100, 999);
                    $deduct = zibpay_update_user_points($user_id, array(
                        'order_num' => $order_num,
                        'value'     => -(int)$price,
                        'type'      => sprintf(__('Mua %s: %s (Gói %s)', 'zibll-nick-decor'), ($type_normalized === 'icon' ? 'Icon nick' : 'Màu nick'), $item['name'], $duration_name),
                        'desc'      => sprintf(__('Thanh toán trang bị %s thời hạn %s', 'zibll-nick-decor'), $item['name'], $duration_name),
                    ));
                    if (!$deduct) {
                        return new WP_Error('payment_failed', __('Giao dịch trừ điểm tích lũy thất bại, vui lòng thử lại sau!', 'zibll-nick-decor'));
                    }
                }
            }
        }

        // Tính thời hạn sử dụng
        $expires_at = 0;
        if ($duration_days > 0) {
            // Nếu đã có item và gia hạn thì cộng dồn ngày
            $base_time = (isset($inv[$bucket][$item_id]['expires_at']) && $inv[$bucket][$item_id]['expires_at'] > time())
                ? $inv[$bucket][$item_id]['expires_at']
                : time();
            $expires_at = $base_time + ($duration_days * 86400);
        }

        // Lưu vào tủ đồ
        $inv[$bucket][$item_id] = array(
            'buy_time'   => time(),
            'expires_at' => $expires_at,
        );
        update_user_meta($user_id, self::META_INVENTORY, $inv);

        // Tự động trang bị ngay sau khi mua
        self::equip_item($user_id, $type_normalized, $item_id);

        return array(
            'success'      => true,
            'message'      => sprintf(__('Mua thành công %s (Gói %s) và đã tự động trang bị cho bạn!', 'zibll-nick-decor'), $item['name'], $duration_name),
            'item_id'      => $item_id,
            'type'         => $type_normalized,
            'duration'     => $duration_days,
            'duration_name'=> $duration_name,
            'expires_at'   => $expires_at,
            'new_balance'  => self::get_user_currency_balance($user_id),
        );
    }

    /**
     * Trang bị một item (Màu hoặc Icon)
     */
    public static function equip_item($user_id, $type, $item_id) {
        if (!$user_id) {
            return new WP_Error('not_logged_in', __('Bạn vui lòng đăng nhập!', 'zibll-nick-decor'));
        }

        $type_normalized = ($type === 'icon' || $type === 'icons') ? 'icon' : 'color';

        if (!self::has_item($user_id, $type_normalized, $item_id)) {
            return new WP_Error('not_owned', __('Bạn chưa sở hữu vật phẩm này!', 'zibll-nick-decor'));
        }

        if ($type_normalized === 'icon') {
            update_user_meta($user_id, self::META_EQUIPPED_ICON, sanitize_text_field($item_id));
        } else {
            update_user_meta($user_id, self::META_EQUIPPED_COLOR, sanitize_text_field($item_id));
        }

        return array(
            'success' => true,
            'message' => __('Đã trang bị thành công!', 'zibll-nick-decor'),
            'type'    => $type_normalized,
            'item_id' => $item_id,
        );
    }

    /**
     * Tháo trang bị một item (Màu hoặc Icon)
     */
    public static function unequip_item($user_id, $type) {
        if (!$user_id) {
            return new WP_Error('not_logged_in', __('Bạn vui lòng đăng nhập!', 'zibll-nick-decor'));
        }

        $type_normalized = ($type === 'icon' || $type === 'icons') ? 'icon' : 'color';

        if ($type_normalized === 'icon') {
            delete_user_meta($user_id, self::META_EQUIPPED_ICON);
        } else {
            delete_user_meta($user_id, self::META_EQUIPPED_COLOR);
        }

        return array(
            'success' => true,
            'message' => __('Đã tháo trang bị thành công!', 'zibll-nick-decor'),
            'type'    => $type_normalized,
        );
    }

    /**
     * Quản trị viên cấp tặng vật phẩm cho thành viên
     */
    public static function admin_grant_item($user_id, $type, $item_id, $duration_days = 0) {
        $inv = self::get_inventory($user_id);
        $bucket = ($type === 'icon' || $type === 'icons') ? 'icons' : 'colors';

        $expires_at = 0;
        if ($duration_days > 0) {
            $expires_at = time() + ($duration_days * 86400);
        }

        $inv[$bucket][$item_id] = array(
            'buy_time'   => time(),
            'expires_at' => $expires_at,
            'granted_by' => get_current_user_id(),
        );

        update_user_meta($user_id, self::META_INVENTORY, $inv);

        // Tự động trang bị
        self::equip_item($user_id, $type, $item_id);

        return true;
    }

    /**
     * Quản trị viên thu hồi vật phẩm của thành viên
     */
    public static function admin_revoke_item($user_id, $type, $item_id) {
        $inv = self::get_inventory($user_id);
        $bucket = ($type === 'icon' || $type === 'icons') ? 'icons' : 'colors';

        if (isset($inv[$bucket][$item_id])) {
            unset($inv[$bucket][$item_id]);
            update_user_meta($user_id, self::META_INVENTORY, $inv);
        }

        // Nếu đang trang bị thì gỡ ra
        $equipped = ($bucket === 'icons') ? self::get_equipped_icon($user_id) : self::get_equipped_color($user_id);
        if ($equipped === $item_id) {
            self::unequip_item($user_id, $type);
        }

        return true;
    }
}
