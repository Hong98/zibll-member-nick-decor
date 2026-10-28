<?php
/**
 * Plugin Name: Zibll Member Nickname & Badge Pro
 * Plugin URI: https://cui.vn/
 * Description: Plugin tùy biến màu sắc và icon hiển thị đầu nickname thời thượng cho thành viên Theme Zibll. Tích hợp trọn bộ icon SVG độc đáo, bảng màu gradient chuyển động, cửa hàng mua sắm bằng Điểm thưởng (Points) hoặc Số dư ví (Balance) và hộp xem trước thời gian thực.
 * Version: 1.0.49
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

// Nâng giới hạn bộ nhớ tối thiểu lên 512M cho Theme Zibll và các module hoạt động mượt mà
if (function_exists('ini_set')) {
    @ini_set('memory_limit', '512M');
}

// Định nghĩa các hằng số plugin
define('ZND_VERSION', '1.0.49');
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
    // Tự động kiểm tra và khởi tạo danh sách icon và màu mặc định (không autoload để tiết kiệm RAM)
    update_option('znd_icons_list', array_values(ZND_Defaults::get_all_icons()), false);
    update_option('znd_colors_list', array_values(ZND_Defaults::get_all_colors()), false);
    update_option('znd_version', ZND_VERSION, false);
}
register_activation_hook(ZND_FILE, 'znd_activate');

/**
 * Khởi tạo plugin
 */
function znd_init() {
    // Tự động đồng bộ các icon và màu mới vào database khi nâng cấp version (không autoload để tiết kiệm RAM)
    if (get_option('znd_version') !== ZND_VERSION) {
        update_option('znd_icons_list', array_values(ZND_Defaults::get_all_icons()), false);
        update_option('znd_colors_list', array_values(ZND_Defaults::get_all_colors()), false);
        update_option('znd_version', ZND_VERSION, false);
    }

    // Loop detector
    add_filter('all', function($hook) {
        static $counts = array();
        if (!isset($counts[$hook])) $counts[$hook] = 0;
        $counts[$hook]++;
        if ($counts[$hook] === 200 || $counts[$hook] === 1000) {
            error_log(sprintf('[LOOP_HOOK] "%s" called %d times! Mem: %dMB', $hook, $counts[$hook], round(memory_get_usage(true)/1048576)));
        }
    });

    add_filter('pre_option', function($pre, $option) {
        static $opt_counts = array();
        if (!isset($opt_counts[$option])) $opt_counts[$option] = 0;
        $opt_counts[$option]++;
        if ($opt_counts[$option] === 200 || $opt_counts[$option] === 1000) {
            error_log(sprintf('[LOOP_OPTION] "%s" requested %d times! Mem: %dMB', $option, $opt_counts[$option], round(memory_get_usage(true)/1048576)));
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 8);
            $frames = array();
            foreach ($trace as $f) {
                $frames[] = ($f['file'] ?? '') . ':' . ($f['line'] ?? '') . ' (' . ($f['function'] ?? '') . ')';
            }
            error_log('[LOOP_OPTION_TRACE] ' . implode(' -> ', $frames));
        }
        return $pre;
    }, 1, 2);

    add_filter('get_post_metadata', function($val, $object_id, $meta_key, $single) {
        static $pm_counts = array();
        $k = $meta_key ? $meta_key : '*all*';
        if (!isset($pm_counts[$k])) $pm_counts[$k] = 0;
        $pm_counts[$k]++;
        if ($pm_counts[$k] === 200 || $pm_counts[$k] === 1000) {
            error_log(sprintf('[LOOP_POST_META] "%s" requested %d times! Mem: %dMB', $k, $pm_counts[$k], round(memory_get_usage(true)/1048576)));
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 8);
            $frames = array();
            foreach ($trace as $f) {
                $frames[] = ($f['file'] ?? '') . ':' . ($f['line'] ?? '') . ' (' . ($f['function'] ?? '') . ')';
            }
            error_log('[LOOP_POST_META_TRACE] ' . implode(' -> ', $frames));
        }
        return $val;
    }, 1, 4);

    add_filter('get_user_metadata', function($val, $object_id, $meta_key, $single) {
        static $um_counts = array();
        $k = $meta_key ? $meta_key : '*all*';
        if (!isset($um_counts[$k])) $um_counts[$k] = 0;
        $um_counts[$k]++;
        if ($um_counts[$k] === 200 || $um_counts[$k] === 1000) {
            error_log(sprintf('[LOOP_USER_META] "%s" requested %d times! Mem: %dMB', $k, $um_counts[$k], round(memory_get_usage(true)/1048576)));
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 8);
            $frames = array();
            foreach ($trace as $f) {
                $frames[] = ($f['file'] ?? '') . ':' . ($f['line'] ?? '') . ' (' . ($f['function'] ?? '') . ')';
            }
            error_log('[LOOP_USER_META_TRACE] ' . implode(' -> ', $frames));
        }
        return $val;
    }, 1, 4);

    // Diagnostic option size check
    if (function_exists('wp_load_alloptions')) {
        $all = wp_load_alloptions();
        $sizes = array();
        $total = 0;
        foreach ($all as $k => $v) {
            $len = strlen(is_string($v) ? $v : serialize($v));
            $sizes[$k] = $len;
            $total += $len;
        }
        arsort($sizes);
        $top5 = array_slice($sizes, 0, 5, true);
        error_log(sprintf('[ZND_MEM_CHECK] Memory: %dMB / %s | Total alloptions: %dKB | Top: %s', round(memory_get_usage(true)/1048576), ini_get('memory_limit'), round($total/1024), json_encode($top5)));
    }

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
