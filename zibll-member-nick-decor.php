<?php
/**
 * Plugin Name: Zibll Member Nickname & Badge Pro
 * Plugin URI: https://cui.vn/
 * Description: Plugin tùy biến màu sắc và icon hiển thị đầu nickname thời thượng cho thành viên Theme Zibll. Tích hợp trọn bộ icon SVG độc đáo, bảng màu gradient chuyển động, cửa hàng mua sắm bằng Điểm thưởng (Points) hoặc Số dư ví (Balance) và hộp xem trước thời gian thực.
 * Version: 1.0.23
 * Author: Cui.vn
 * Author URI: https://cui.vn/
 * Text Domain: zibll-nick-decor
 * Domain Path: /languages
 * Requires at least: 5.6
 * Requires PHP: 7.2
 */

if (!defined('ABSPATH')) {
    exit;
}

// Định nghĩa các hằng số plugin
define('ZND_VERSION', '1.0.23');
define('ZND_FILE', __FILE__);
define('ZND_PATH', plugin_dir_path(__FILE__));
define('ZND_URL', plugin_dir_url(__FILE__));
define('ZND_BASENAME', plugin_basename(__FILE__));

// Nạp các lớp xử lý
require_once ZND_PATH . 'includes/class-nick-decor-defaults.php';
require_once ZND_PATH . 'includes/class-nick-decor-options.php';
require_once ZND_PATH . 'includes/class-nick-decor-user.php';
require_once ZND_PATH . 'includes/class-nick-decor-renderer.php';
require_once ZND_PATH . 'includes/class-nick-decor-ajax.php';

/**
 * Xử lý khi kích hoạt plugin
 */
function znd_activate() {
    // Tự động kiểm tra và khởi tạo danh sách icon và màu mặc định nếu chưa lưu
    $icons = get_option('znd_icons_list', null);
    if ($icons === null || !is_array($icons)) {
        update_option('znd_icons_list', array_values(ZND_Defaults::get_default_icons()));
    }

    $colors = get_option('znd_colors_list', null);
    if ($colors === null || !is_array($colors)) {
        update_option('znd_colors_list', array_values(ZND_Defaults::get_default_colors()));
    }
}
register_activation_hook(ZND_FILE, 'znd_activate');

/**
 * Khởi tạo plugin
 */
function znd_init() {
    // Khởi tạo Admin Options Codestar Framework
    ZND_Options::init();

    // Khởi tạo AJAX Handlers
    ZND_Ajax::init();

    // Khởi tạo Frontend Renderer
    ZND_Renderer::get_instance();

    // Nạp Admin CSS & JS khi ở trang Cài đặt Theme Zibll
    if (is_admin()) {
        add_action('admin_enqueue_scripts', 'znd_admin_enqueue_scripts');
    }

    // Đã tích hợp nút mở nhanh qua zib_nav_radius_button và injectUserMenuButton
}
add_action('plugins_loaded', 'znd_init');

/**
 * Nạp CSS & JS trong khu vực Quản trị Admin Theme Zibll
 */
function znd_admin_enqueue_scripts($hook) {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    $is_zibll_options = false;

    if ($hook === 'toplevel_page_zibll_options') {
        $is_zibll_options = true;
    } elseif ($screen && (!empty($screen->id) && strpos($screen->id, 'zibll_options') !== false)) {
        $is_zibll_options = true;
    } elseif (isset($_GET['page']) && $_GET['page'] === 'zibll_options') {
        $is_zibll_options = true;
    }

    if (!$is_zibll_options) {
        return;
    }

    $css_ver = file_exists(ZND_PATH . 'assets/css/admin.css') ? filemtime(ZND_PATH . 'assets/css/admin.css') : ZND_VERSION;
    $js_ver  = file_exists(ZND_PATH . 'assets/js/admin.js') ? filemtime(ZND_PATH . 'assets/js/admin.js') : ZND_VERSION;

    wp_enqueue_style(
        'znd-admin',
        ZND_URL . 'assets/css/admin.css',
        array(),
        $css_ver
    );

    wp_enqueue_script(
        'znd-admin',
        ZND_URL . 'assets/js/admin.js',
        array('jquery'),
        $js_ver,
        true
    );
}

/**
 * Thêm liên kết Cài đặt trong trang danh sách Plugins của WordPress
 */
function znd_plugin_action_links($links) {
    $settings_url = admin_url('admin.php?page=zibll_options#tab=znd_sub_general');
    $settings_link = '<a href="' . esc_url($settings_url) . '">' . __('Cài đặt', 'zibll-nick-decor') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . ZND_BASENAME, 'znd_plugin_action_links');
