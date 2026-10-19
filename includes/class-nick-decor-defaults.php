<?php
/**
 * Zibll Member Nickname & Badge Pro - Dữ liệu cấu hình mặc định (Màu & Icon)
 *
 * @package Zibll_Member_Nick_Decor
 */

if (!defined('ABSPATH')) {
    exit;
}

class ZND_Defaults {

    /**
     * Danh sách 16 icon đầu nick tạo sẵn siêu đẹp với SVG vector chuẩn
     */
    public static function get_default_icons() {
        return array(
            'crown_gold' => array(
                'id'             => 'crown_gold',
                'name'           => 'Vương miện hoàng gia',
                'desc'           => 'Biểu tượng vương giả vàng kim đính ngọc ruby cao quý - Đặc quyền Quản trị viên cấp cao',
                'price'          => 200,
                'duration'       => 0, // 0 = Vĩnh viễn
                'animation'      => 'shimmer',
                'vip_level'      => 0,
                'status'         => true,
                'role_exclusive' => 'admin',
                'svg'            => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><path d="M3 17 L2 6.5 L7.5 11 L12 3 L16.5 11 L22 6.5 L21 17 Z" fill="#FFA751"/><rect x="2" y="18" width="20" height="3" rx="1.5" fill="#E65100"/><circle cx="2" cy="6.5" r="1.1" fill="#FFE259"/><circle cx="12" cy="3" r="1.4" fill="#FFE259"/><circle cx="22" cy="6.5" r="1.1" fill="#FFE259"/><circle cx="12" cy="14" r="1.5" fill="#FFFFFF"/><circle cx="6.5" cy="14.5" r="1.2" fill="#FFFFFF"/><circle cx="17.5" cy="14.5" r="1.2" fill="#FFFFFF"/></svg>',
            ),
            'diamond_blue' => array(
                'id'          => 'diamond_blue',
                'name'        => 'Kim cương chí tôn',
                'desc'        => 'Viên kim cương xanh lam vĩnh cửu phát sáng hologram',
                'price'       => 250,
                'duration'    => 0,
                'animation'   => 'glow',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_diamond" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#A8EDEA"/><stop offset="50%" stop-color="#00D2FF"/><stop offset="100%" stop-color="#0072FF"/></linearGradient></defs><path d="M6 3h12l5 6-11 12L1 9l5-6z" fill="url(#znd_g_diamond)"/><path d="M1 9h22M6 3l6 18M18 3l-6 18M6 3l6 6-6-6zm12 0l-6 6 6-6z" stroke="#FFF" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            ),
            'flame_fire' => array(
                'id'          => 'flame_fire',
                'name'        => 'Ngọn lửa rực cháy',
                'desc'        => 'Ngọn lửa ma thuật cam đỏ bùng nổ nhiệt huyết và đam mê',
                'price'       => 150,
                'duration'    => 0,
                'animation'   => 'pulse',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_fire" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" stop-color="#FFF066"/><stop offset="40%" stop-color="#FF4E50"/><stop offset="100%" stop-color="#F9D423"/></linearGradient></defs><path d="M12 2c-.5 2.5-3 5-3 8 0 1.5.5 3 2 4-2-.5-3.5-2-3.5-4 0-1.5.5-2.5 1-3.5C6 8 4 10.5 4 14c0 4.4 3.6 8 8 8s8-3.6 8-8c0-4.5-3.5-7.5-6-10-1 2-2 3.5-2 5 0 1.5 1 2.5 1 3.5-1.5-.5-2.5-2-2-4.5.5-1.5 1-3 1-5z" fill="url(#znd_g_fire)"/></svg>',
            ),
            'lightning_bolt' => array(
                'id'          => 'lightning_bolt',
                'name'        => 'Tia sét thần sấm',
                'desc'        => 'Sấm sét hoàng gia uy lực chớp sáng năng lượng vô tận',
                'price'       => 180,
                'duration'    => 0,
                'animation'   => 'glow',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_bolt" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#FFE000"/><stop offset="100%" stop-color="#799F0C"/></linearGradient></defs><path d="M13 2L3 14h8l-2 8 12-13h-8l2-7z" fill="url(#znd_g_bolt)" stroke="#FFF" stroke-width="0.8" stroke-linejoin="round"/></svg>',
            ),
            'angel_wings' => array(
                'id'          => 'angel_wings',
                'name'        => 'Cánh thiên thần',
                'desc'        => 'Đôi cánh thiên sứ ánh bạc hào quang thanh khiết',
                'price'       => 300,
                'duration'    => 0,
                'animation'   => 'float',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_wings" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#A8EDEA"/><stop offset="100%" stop-color="#FED6E3"/></linearGradient></defs><path d="M12 12c-2-4-5-8-11-8 1 4 2 8 6 10-3 0-5-2-6-4 0 4 3 7 7 8-3 1-5 0-6-1 1 3 4 5 7 5 3 0 5-4 5-10zm0 0c2-4 5-8 11-8-1 4-2 8-6 10 3 0 5-2 6-4 0 4-3 7-7 8 3 1 5 0 6-1-1 3-4 5-7 5-3 0-5-4-5-10z" fill="url(#znd_g_wings)" stroke="#FFF" stroke-width="0.6"/></svg>',
            ),
            'rocket_cyber' => array(
                'id'          => 'rocket_cyber',
                'name'        => 'Tàu không gian Cyber',
                'desc'        => 'Phi thuyền công nghệ tương lai bứt phá mọi giới hạn',
                'price'       => 220,
                'duration'    => 0,
                'animation'   => 'float',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_rocket" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#F857A6"/><stop offset="100%" stop-color="#FF5858"/></linearGradient></defs><path d="M13.5 2.5a10 10 0 0 0-3 3C9 7 9 9 9 9l-4 4 3 3 4-4s2 0 3.5-1.5a10 10 0 0 0 3-3c0-3-2.5-5-5-5z" fill="url(#znd_g_rocket)"/><path d="M5 13l-3 3 3 1-1 3 3-1 1 3 3-3" fill="#FFA07A"/><circle cx="15.5" cy="8.5" r="1.5" fill="#FFF"/></svg>',
            ),
            'shield_knight' => array(
                'id'             => 'shield_knight',
                'name'           => 'Khiên hiệp sĩ',
                'desc'           => 'Tấm khiên danh dự bảo hộ kiên cường của dũng sĩ - Đặc quyền Điều hành viên',
                'price'          => 160,
                'duration'       => 0,
                'animation'      => 'shimmer',
                'vip_level'      => 0,
                'status'         => true,
                'role_exclusive' => 'mod',
                'svg'            => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_shield" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#4FACFE"/><stop offset="100%" stop-color="#00F2FE"/></linearGradient></defs><path d="M12 2L3 6v6c0 6.5 4 10 9 11 5-1 9-4.5 9-11V6l-9-4z" fill="url(#znd_g_shield)"/><path d="M12 5v14M7 11h10" stroke="#FFF" stroke-width="1.8" stroke-linecap="round"/></svg>',
            ),
            'star_glory' => array(
                'id'          => 'star_glory',
                'name'        => 'Ngôi sao vinh quang',
                'desc'        => 'Ngôi sao 5 cánh tỏa sáng lấp lánh ánh hào quang thành công',
                'price'       => 140,
                'duration'    => 0,
                'animation'   => 'shimmer',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_star" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#F6D365"/><stop offset="100%" stop-color="#FDA085"/></linearGradient></defs><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="url(#znd_g_star)" stroke="#FFF" stroke-width="0.8"/></svg>',
            ),
            'trophy_champion' => array(
                'id'          => 'trophy_champion',
                'name'        => 'Cúp vô địch',
                'desc'        => 'Chiếc cúp vàng danh giá dành riêng cho những quán quân',
                'price'       => 280,
                'duration'    => 0,
                'animation'   => 'glow',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_trophy" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#F7971E"/><stop offset="100%" stop-color="#FFD200"/></linearGradient></defs><path d="M6 3h12v7c0 3.3-2.7 6-6 6s-6-2.7-6-6V3zm0 2H2v3c0 2 1.5 3.5 3.5 3.8M18 5h4v3c0 2-1.5 3.5-3.5 3.8M12 16v3M8 21h8" stroke="url(#znd_g_trophy)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 3h12v7c0 3.3-2.7 6-6 6s-6-2.7-6-6V3z" fill="url(#znd_g_trophy)"/></svg>',
            ),
            'lucky_clover' => array(
                'id'          => 'lucky_clover',
                'name'        => 'Cỏ 4 lá may mắn',
                'desc'        => 'Biểu tượng xanh tươi may mắn, tài lộc và hạnh phúc tràn đầy',
                'price'       => 120,
                'duration'    => 0,
                'animation'   => 'pulse',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_clover" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#11998E"/><stop offset="100%" stop-color="#38EF7D"/></linearGradient></defs><path d="M12 12c-2-3-4-5-6-4-2 1-2 4 0 6 2 2 4 1 6-2zm0 0c2-3 4-5 6-4 2 1 2 4 0 6-2 2-4 1-6-2zm0 0c-3 2-5 4-4 6 1 2 4 2 6 0 2-2 1-4-2-6zm0 0c3 2 5 4 4 6-1 2-4 2-6 0-2-2-1-4 2-6z" fill="url(#znd_g_clover)"/><path d="M12 12v9" stroke="#11998E" stroke-width="1.5" stroke-linecap="round"/></svg>',
            ),
            'heart_neon' => array(
                'id'          => 'heart_neon',
                'name'        => 'Trái tim Neon',
                'desc'        => 'Trái tim hồng neon rực rỡ lan tỏa tình yêu thương',
                'price'       => 130,
                'duration'    => 0,
                'animation'   => 'pulse',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_heart" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#FF0844"/><stop offset="100%" stop-color="#FFB199"/></linearGradient></defs><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="url(#znd_g_heart)" stroke="#FFF" stroke-width="0.8"/></svg>',
            ),
            'dragon_head' => array(
                'id'          => 'dragon_head',
                'name'        => 'Rồng lửa huyền thoại',
                'desc'        => 'Đầu thần rồng uy nghiêm với sức mạnh áp đảo thiên hạ',
                'price'       => 350,
                'duration'    => 0,
                'animation'   => 'glow',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_dragon" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#E65C00"/><stop offset="100%" stop-color="#F9D423"/></linearGradient></defs><path d="M2 13c3-1 6-4 9-7 1 3 4 5 7 5 2 0 4-1 4-1s-1 3-3 5c2 0 3 1 3 1s-2 3-5 3c-2 0-3-1-5-1-2 2-5 3-7 3s-3-2-3-8z" fill="url(#znd_g_dragon)"/><circle cx="16" cy="11" r="1.2" fill="#FF0000"/><path d="M10 17l-3 3M14 18l-2 3" stroke="#FFF" stroke-width="1" stroke-linecap="round"/></svg>',
            ),
            'sword_master' => array(
                'id'          => 'sword_master',
                'name'        => 'Song kiếm hiệp sĩ',
                'desc'        => 'Hai thanh kiếm ánh thép trượng nghĩa của bậc đại hiệp',
                'price'       => 190,
                'duration'    => 0,
                'animation'   => 'shimmer',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_sword" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#8E9EAB"/><stop offset="100%" stop-color="#EEF2F3"/></linearGradient></defs><path d="M3 21l6-6M21 3l-6 6m0 0l-4-1-6 6 1 4 4 1 6-6-1-4z" stroke="url(#znd_g_sword)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 21l-6-6M3 3l6 6m0 0l4-1 6 6-1 4-4 1-6-6 1-4z" stroke="#4facfe" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            ),
            'magic_wand' => array(
                'id'          => 'magic_wand',
                'name'        => 'Đũa phép ma thuật',
                'desc'        => 'Cây đũa phép tỏa sáng các vì tinh tú diệu kỳ',
                'price'       => 210,
                'duration'    => 0,
                'animation'   => 'shimmer',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_wand" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#C471ED"/><stop offset="100%" stop-color="#F64F59"/></linearGradient></defs><path d="M4 20L15 9l-1-1L3 19l1 1zm14.5-9.5l1-2 2-1-2-1-1-2-1 2-2 1 2 1 1 2zm-7-6l.6-1.4 1.4-.6-1.4-.6-.6-1.4-.6 1.4-1.4.6 1.4.6.6 1.4z" fill="url(#znd_g_wand)"/><path d="M14 8l2 2" stroke="#FFF" stroke-width="1.2"/></svg>',
            ),
            'music_note' => array(
                'id'          => 'music_note',
                'name'        => 'Nốt nhạc thần tiên',
                'desc'        => 'Nốt nhạc đôi giai điệu ngọt ngào của những tâm hồn nghệ sĩ',
                'price'       => 110,
                'duration'    => 0,
                'animation'   => 'float',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_music" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#FA709A"/><stop offset="100%" stop-color="#FEE140"/></linearGradient></defs><path d="M9 18V5l12-2v13M9 18a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm12-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" fill="url(#znd_g_music)"/><path d="M9 9l12-2" stroke="#FFF" stroke-width="1.2"/></svg>',
            ),
            'cat_noble' => array(
                'id'          => 'cat_noble',
                'name'        => 'Mèo quý tộc',
                'desc'        => 'Mặt mèo đáng yêu đội vương miện nhỏ ngộ nghĩnh siêu cute',
                'price'       => 170,
                'duration'    => 0,
                'animation'   => 'pulse',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_cat" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#FBC2EB"/><stop offset="100%" stop-color="#A6C1EE"/></linearGradient></defs><path d="M12 8c4 0 7 3 7 7 0 4-3 6-7 6s-7-2-7-6c0-4 3-7 7-7zm-5-3l2 4M17 5l-2 4" stroke="url(#znd_g_cat)" stroke-width="1.5" stroke-linecap="round"/><circle cx="9" cy="14" r="1.2" fill="#333"/><circle cx="15" cy="14" r="1.2" fill="#333"/><path d="M11 16.5c.5.5 1.5.5 2 0" stroke="#FF5858" stroke-width="1.2" stroke-linecap="round"/><path d="M10 4l2-2 2 2-1 1h-2l-1-1z" fill="#FFA751"/></svg>',
            ),
            'lock_banned' => array(
                'id'             => 'lock_banned',
                'name'           => 'Khóa cấm tài khoản',
                'desc'           => 'Biểu tượng ổ khóa cảnh báo áp dụng cho tài khoản đang bị khóa do vi phạm',
                'price'          => 0,
                'duration'       => 0,
                'animation'      => 'none',
                'vip_level'      => 0,
                'status'         => true,
                'role_exclusive' => 'banned',
                'svg'            => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="#e02424" stroke-width="2" stroke-linecap="round"/><rect x="4" y="11" width="16" height="11" rx="2.5" fill="#f05252"/><circle cx="12" cy="16" r="1.5" fill="#FFF"/><path d="M12 17.5v2" stroke="#FFF" stroke-width="1.5" stroke-linecap="round"/></svg>',
            ),
        );
    }

    /**
     * Danh sách 12 màu nick tạo sẵn siêu đẹp với Gradient & Glow
     */
    public static function get_default_colors() {
        return array(
            'color_rainbow' => array(
                'id'          => 'color_rainbow',
                'name'        => 'Cầu vồng rực rỡ',
                'desc'        => 'Dải 7 sắc cầu vồng chuyển màu động liên tục lộng lẫy',
                'price'       => 300,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(90deg, #ff0000, #ff7f00, #ffff00, #00ff00, #00ffff, #0000ff, #8b00ff, #ff0000); background-size: 200% auto; -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;',
                'animation'   => 'rainbow',
                'glow_color'  => 'rgba(255, 105, 180, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_gold' => array(
                'id'          => 'color_gold',
                'name'        => 'Hoàng gia kim sắc',
                'desc'        => 'Màu vàng hoàng kim ánh kim loại sang trọng của bậc vương giả',
                'price'       => 250,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #FFE259 0%, #FFA751 50%, #FF5E3A 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(255, 167, 81, 0.2);',
                'animation'   => 'shimmer',
                'glow_color'  => 'rgba(255, 167, 81, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_cyber_neon' => array(
                'id'          => 'color_cyber_neon',
                'name'        => 'Cyberpunk Neon',
                'desc'        => 'Tím hồng Neon công nghệ tương lai phá cách và hiện đại',
                'price'       => 220,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #F72585 0%, #7209B7 50%, #4CC9F0 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;',
                'animation'   => 'glow',
                'glow_color'  => 'rgba(247, 37, 133, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_ocean_blue' => array(
                'id'          => 'color_ocean_blue',
                'name'        => 'Xanh lam đại dương',
                'desc'        => 'Sắc xanh ngọc bích sâu thẳm phát sáng băng thanh ngọc khiết',
                'price'       => 180,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #00C6FF 0%, #0072FF 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;',
                'animation'   => 'none',
                'glow_color'  => 'rgba(0, 198, 255, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_fire_red' => array(
                'id'          => 'color_fire_red',
                'name'        => 'Lửa đỏ rực cháy',
                'desc'        => 'Sắc đỏ cam bùng cháy năng lượng mãnh liệt và quyền năng',
                'price'       => 200,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #FF416C 0%, #FF4B2B 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(255, 65, 108, 0.2);',
                'animation'   => 'pulse',
                'glow_color'  => 'rgba(255, 75, 43, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_emerald' => array(
                'id'             => 'color_emerald',
                'name'           => 'Ngọc lục bảo',
                'desc'           => 'Màu xanh ngọc bích lục bảo quý phái trường tồn - Đặc quyền Điều hành viên',
                'price'          => 160,
                'duration'       => 0,
                'css_style'      => 'background: linear-gradient(135deg, #11998E 0%, #38EF7D 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;',
                'animation'      => 'none',
                'glow_color'     => 'rgba(56, 239, 125, 0.25)',
                'vip_level'      => 0,
                'status'         => true,
                'role_exclusive' => 'mod',
            ),
            'color_sakura' => array(
                'id'          => 'color_sakura',
                'name'        => 'Hồng hoa anh đào',
                'desc'        => 'Hồng phấn hoa đào Sakura ngọt ngào dễ thương và lãng mạn',
                'price'       => 150,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #FF758C 0%, #FF7EB3 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;',
                'animation'   => 'none',
                'glow_color'  => 'rgba(255, 117, 140, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_electric' => array(
                'id'          => 'color_electric',
                'name'        => 'Tím điện tử Storm',
                'desc'        => 'Tím pha xanh neon cá tính của những tia chớp bão từ',
                'price'       => 190,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;',
                'animation'   => 'glow',
                'glow_color'  => 'rgba(102, 126, 234, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_sunset' => array(
                'id'          => 'color_sunset',
                'name'        => 'Hoàng hôn nhiệt đới',
                'desc'        => 'Gam màu cam đào hoàng hôn ấm áp lãng mạn bên bờ biển',
                'price'       => 170,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #FA709A 0%, #FEE140 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;',
                'animation'   => 'none',
                'glow_color'  => 'rgba(250, 112, 154, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_galaxy' => array(
                'id'          => 'color_galaxy',
                'name'        => 'Dải ngân hà Cosmic',
                'desc'        => 'Vũ trụ huyền bí sâu thẳm lấp lánh ánh tinh tú ngàn sao',
                'price'       => 260,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #654EA3 0%, #EAAFC8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(101, 78, 163, 0.2);',
                'animation'   => 'shimmer',
                'glow_color'  => 'rgba(101, 78, 163, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_neon_mint' => array(
                'id'          => 'color_neon_mint',
                'name'        => 'Bạc hà Neon Mint',
                'desc'        => 'Xanh bạc hà pha ánh dương sảng khoái và tràn đầy sinh lực',
                'price'       => 140,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #00F260 0%, #0575E6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;',
                'animation'   => 'none',
                'glow_color'  => 'rgba(0, 242, 96, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_ruby_glow' => array(
                'id'             => 'color_ruby_glow',
                'name'           => 'Đỏ Ruby phát sáng',
                'desc'           => 'Màu ngọc ruby huyết phượng hoàng phát sáng lộng lẫy - Đặc quyền Quản trị viên cấp cao',
                'price'          => 240,
                'duration'       => 0,
                'css_style'      => 'background: linear-gradient(135deg, #CB2D3E 0%, #EF473A 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(239, 71, 58, 0.2);',
                'animation'      => 'glow',
                'glow_color'     => 'rgba(239, 71, 58, 0.25)',
                'vip_level'      => 0,
                'status'         => true,
                'role_exclusive' => 'admin',
            ),
            'color_banned' => array(
                'id'             => 'color_banned',
                'name'           => 'Xám gạch ngang (Bị khóa)',
                'desc'           => 'Màu xám gạch ngang nhận diện tài khoản đang bị khóa do vi phạm',
                'price'          => 0,
                'duration'       => 0,
                'css_style'      => 'color: #8c939d !important; -webkit-text-fill-color: #8c939d !important; text-decoration: line-through !important; background: none !important; opacity: 0.65; filter: grayscale(100%); font-weight: normal;',
                'animation'      => 'none',
                'glow_color'     => '',
                'vip_level'      => 0,
                'status'         => true,
                'role_exclusive' => 'banned',
            ),
        );
    }

    /**
     * Lấy danh sách icon hiện hành (từ cấu hình theme hoặc mặc định)
     */
    public static function get_all_icons() {
        $defaults = self::get_default_icons();
        $saved = null;
        if (function_exists('_pz')) {
            $saved = _pz('znd_icons_list');
        }
        if (empty($saved) || !is_array($saved)) {
            $saved = get_option('znd_icons_list', null);
        }

        if (empty($saved) || !is_array($saved)) {
            return $defaults;
        }

        $result = array();
        foreach ($saved as $item) {
            if (!empty($item['id'])) {
                $id = $item['id'];
                // Tự động nâng cấp SVG cũ lỗi trong database lên SVG chuẩn mới
                if (isset($defaults[$id])) {
                    if (empty($item['svg']) || strpos($item['svg'], 'zm0-14') !== false || strpos($item['svg'], 'znd_g_crown') !== false) {
                        $item['svg'] = $defaults[$id]['svg'];
                    }
                    if (isset($defaults[$id]['role_exclusive'])) {
                        $item['role_exclusive'] = $defaults[$id]['role_exclusive'];
                    }
                }
                $result[$id] = $item;
            }
        }

        // Bổ sung các icon mặc định nếu chưa có
        foreach ($defaults as $id => $def_item) {
            if (!isset($result[$id])) {
                $result[$id] = $def_item;
            }
        }

        return !empty($result) ? $result : $defaults;
    }

    /**
     * Lấy danh sách màu hiện hành (từ cấu hình theme hoặc mặc định)
     */
    public static function get_all_colors() {
        $saved = null;
        if (function_exists('_pz')) {
            $saved = _pz('znd_colors_list');
        }
        if (empty($saved) || !is_array($saved)) {
            $saved = get_option('znd_colors_list', null);
        }

        $defaults = self::get_default_colors();

        if (empty($saved) || !is_array($saved)) {
            return $defaults;
        }

        $result = array();
        foreach ($saved as $item) {
            if (!empty($item['id'])) {
                $id = $item['id'];
                if (!empty($item['css_style'])) {
                    // Tự động làm sạch bóng mờ text-shadow cũ quá dày (10px, 12px...) xuống mức thanh mảnh 1px 2px
                    $item['css_style'] = preg_replace('/text-shadow:\s*0\s+0\s+(?:10|12|\d{2,})px\s+rgba\(([^,\)]+),([^,\)]+),([^,\)]+),\s*([0-9.]+)\);?/i', 'text-shadow: 0 1px 2px rgba($1,$2,$3,0.2);', $item['css_style']);
                }
                if (isset($defaults[$id]['role_exclusive'])) {
                    $item['role_exclusive'] = $defaults[$id]['role_exclusive'];
                }
                $result[$id] = $item;
            }
        }

        // Bổ sung các màu mặc định nếu chưa có
        foreach ($defaults as $id => $def_item) {
            if (!isset($result[$id])) {
                $result[$id] = $def_item;
            }
        }

        return !empty($result) ? $result : $defaults;
    }

    /**
     * Lấy chi tiết một icon theo ID
     */
    public static function get_icon($id) {
        $icons = self::get_all_icons();
        return isset($icons[$id]) ? $icons[$id] : false;
    }

    /**
     * Lấy chi tiết một màu theo ID
     */
    public static function get_color($id) {
        $colors = self::get_all_colors();
        return isset($colors[$id]) ? $colors[$id] : false;
    }

    /**
     * Lấy danh sách các gói thời hạn và giá tương ứng cho một vật phẩm
     *
     * @param float $base_price Giá cơ sở hiện tại của vật phẩm
     * @return array Danh sách các gói (7 ngày, 30 ngày, Vĩnh viễn)
     */
    public static function get_duration_options($base_price = 0) {
        $base_price    = (float)$base_price;
        $currency_name = class_exists('ZND_User') ? ZND_User::get_currency_name() : __('Điểm', 'zibll-nick-decor');

        $multiplier_7       = 10;
        $multiplier_30      = 20;
        $multiplier_forever = 30;

        if (function_exists('_pz')) {
            $m7  = (int)_pz('znd_price_multiplier_7', 10);
            $m30 = (int)_pz('znd_price_multiplier_30', 20);
            $mf  = (int)_pz('znd_price_multiplier_forever', 30);
            if ($m7 > 0) $multiplier_7 = $m7;
            if ($m30 > 0) $multiplier_30 = $m30;
            if ($mf > 0) $multiplier_forever = $mf;
        }

        $price_7       = ($base_price > 0) ? round($base_price * $multiplier_7) : 0;
        $price_30      = ($base_price > 0) ? round($base_price * $multiplier_30) : 0;
        $price_forever = ($base_price > 0) ? round($base_price * $multiplier_forever) : 0;

        return array(
            7 => array(
                'days'        => 7,
                'name'        => __('7 ngày', 'zibll-nick-decor'),
                'multiplier'  => $multiplier_7,
                'price'       => $price_7,
                'price_text'  => ($price_7 > 0) ? number_format($price_7) . ' ' . $currency_name : __('Miễn phí', 'zibll-nick-decor'),
                'badge'       => '',
            ),
            30 => array(
                'days'        => 30,
                'name'        => __('30 ngày', 'zibll-nick-decor'),
                'multiplier'  => $multiplier_30,
                'price'       => $price_30,
                'price_text'  => ($price_30 > 0) ? number_format($price_30) . ' ' . $currency_name : __('Miễn phí', 'zibll-nick-decor'),
                'badge'       => '',
            ),
            0 => array(
                'days'        => 0,
                'name'        => __('Vĩnh viễn', 'zibll-nick-decor'),
                'multiplier'  => $multiplier_forever,
                'price'       => $price_forever,
                'price_text'  => ($price_forever > 0) ? number_format($price_forever) . ' ' . $currency_name : __('Miễn phí', 'zibll-nick-decor'),
                'badge'       => __('Khuyên dùng', 'zibll-nick-decor'),
            ),
        );
    }

    /**
     * Lấy giá của vật phẩm theo số ngày được chọn
     *
     * @param float $base_price Giá cơ sở của vật phẩm
     * @param int   $duration_days Số ngày (7, 30, hoặc 0 cho vĩnh viễn)
     * @return float Giá tính theo gói thời hạn
     */
    public static function get_item_price_by_duration($base_price, $duration_days = 7) {
        $options = self::get_duration_options($base_price);
        $duration_days = (int)$duration_days;
        if (isset($options[$duration_days])) {
            return $options[$duration_days]['price'];
        }
        return isset($options[7]) ? $options[7]['price'] : round((float)$base_price * 10);
    }

    /**
     * Kiểm tra người dùng có phải Quản trị viên cấp cao (Admin) hay không
     */
    public static function is_user_admin($user_id = 0) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        if (!$user_id) {
            return false;
        }
        return is_super_admin($user_id) || user_can($user_id, 'manage_options');
    }

    /**
     * Kiểm tra người dùng có phải Điều hành viên (Mod/Editor - dưới Admin và trên thành viên) hay không
     */
    public static function is_user_mod($user_id = 0) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        if (!$user_id) {
            return false;
        }
        // Nếu đã là admin thì không tính là mod (admin là cấp cao nhất)
        if (self::is_user_admin($user_id)) {
            return false;
        }
        // Kiểm tra quyền kiểm duyệt hoặc chỉnh sửa
        if (user_can($user_id, 'moderate_comments') || user_can($user_id, 'edit_others_posts')) {
            return true;
        }
        $user = get_userdata($user_id);
        if ($user && !empty($user->roles)) {
            $mod_roles = array('editor', 'moderator', 'bbp_moderator', 'zib_moderator');
            if (array_intersect($mod_roles, (array)$user->roles)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Kiểm tra người dùng có đang bị khóa / bị BAN hay không
     */
    public static function is_user_banned($user_id = 0) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        if (!$user_id) {
            return false;
        }
        // 1. Kiểm tra qua hàm zib_user_is_ban của Theme Zibll
        if (function_exists('zib_user_is_ban') && zib_user_is_ban($user_id)) {
            return true;
        }
        // 2. Kiểm tra trực tiếp qua user meta của Theme Zibll
        $banned = get_user_meta($user_id, 'banned', true);
        if (!empty($banned)) {
            $ban_time = get_user_meta($user_id, 'banned_time', true);
            if ($ban_time && strtotime($ban_time) < time()) {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Kiểm tra người dùng có đủ điều kiện sở hữu một vật phẩm đặc quyền theo vai trò hay không
     */
    public static function can_user_own_item($user_id, $role_exclusive) {
        if (empty($role_exclusive)) {
            return true;
        }
        if ($role_exclusive === 'admin') {
            return self::is_user_admin($user_id);
        }
        if ($role_exclusive === 'mod') {
            return self::is_user_mod($user_id);
        }
        if ($role_exclusive === 'banned') {
            return self::is_user_banned($user_id);
        }
        return false;
    }
}

