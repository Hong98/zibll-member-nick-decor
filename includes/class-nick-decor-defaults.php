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
        static $cached = null;
        if ($cached !== null) {
            return $cached;
        }
        $cached = array(
            'admin_supreme' => array(
                'id'             => 'admin_supreme',
                'name'           => 'Huy hiệu Quản trị tối cao',
                'desc'           => 'Huy hiệu khiên vương miện ngôi sao vinh quang vàng rực rỡ - Biểu tượng Ban Quản trị tối cao',
                'price'          => 0,
                'duration'       => 0,
                'animation'      => 'shimmer',
                'vip_level'      => 0,
                'status'         => true,
                'role_exclusive' => 'admin',
                'svg'            => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><path d="M4 10c0 6.5 4.5 10.5 8 12 3.5-1.5 8-5.5 8-12V5l-8-3-8 3v5z" fill="#FFA751"/><path d="M5.5 10.5c0 5.2 3.8 8.7 6.5 10 2.7-1.3 6.5-4.8 6.5-10V6.3L12 3.9 5.5 6.3v4.2z" fill="#E65100"/><path d="M7 6l2.5 1.5L12 5l2.5 2.5L17 6v2l-5 3-5-3V6z" fill="#FFE259"/><polygon points="12,8.5 13.3,11.2 16.2,11.5 14,13.5 14.6,16.4 12,14.9 9.4,16.4 10,13.5 7.8,11.5 10.7,11.2" fill="#FFFFFF"/><circle cx="12" cy="12.5" r="1.2" fill="#FF2A6D"/><circle cx="12" cy="4.5" r="0.8" fill="#FFFFFF"/></svg>',
            ),
            'mod_justice' => array(
                'id'             => 'mod_justice',
                'name'           => 'Búa công lý điều hành',
                'desc'           => 'Chiếc búa phán quyết công lý mạ ngọc lục bảo uy nghiêm - Biểu tượng Điều hành viên giữ gìn trật tự',
                'price'          => 0,
                'duration'       => 0,
                'animation'      => 'glow',
                'vip_level'      => 0,
                'status'         => true,
                'role_exclusive' => 'mod',
                'svg'            => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><path d="M14.5 3.5l6 6-2 2-6-6 2-2z" fill="#11998E"/><rect x="13" y="4" width="3" height="8" rx="1" transform="rotate(-45 13 4)" fill="#38EF7D"/><rect x="19" y="10" width="3" height="8" rx="1" transform="rotate(-45 19 10)" fill="#38EF7D"/><path d="M12.5 8.5l-8.5 8.5a1.5 1.5 0 0 0 0 2.1l1.4 1.4a1.5 1.5 0 0 0 2.1 0l8.5-8.5-3.5-3.5z" fill="#E0EAFC"/><path d="M5.4 17l1.6 1.6-1.4 1.4a1 1 0 0 1-1.4 0l-.2-.2a1 1 0 0 1 0-1.4l1.4-1.4z" fill="#11998E"/><path d="M3 21h7" stroke="#38EF7D" stroke-width="2" stroke-linecap="round"/><circle cx="5" cy="5" r="1" fill="#38EF7D"/><circle cx="20" cy="18" r="1" fill="#38EF7D"/><circle cx="10" cy="2" r="1" fill="#FFFFFF"/></svg>',
            ),
            'crown_gold' => array(
                'id'          => 'crown_gold',
                'name'        => 'Vương miện hoàng gia',
                'desc'        => 'Biểu tượng vương giả vàng kim đính ngọc hoàng gia cao quý',
                'price'       => 200,
                'duration'    => 0, // 0 = Vĩnh viễn
                'animation'   => 'shimmer',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><path d="M3 17 L2 6.5 L7.5 11 L12 3 L16.5 11 L22 6.5 L21 17 Z" fill="#FFA751"/><rect x="2" y="18" width="20" height="3" rx="1.5" fill="#E65100"/><circle cx="2" cy="6.5" r="1.1" fill="#FFE259"/><circle cx="12" cy="3" r="1.4" fill="#FFE259"/><circle cx="22" cy="6.5" r="1.1" fill="#FFE259"/><circle cx="12" cy="14" r="1.5" fill="#FFFFFF"/><circle cx="6.5" cy="14.5" r="1.2" fill="#FFFFFF"/><circle cx="17.5" cy="14.5" r="1.2" fill="#FFFFFF"/></svg>',
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
                'id'          => 'shield_knight',
                'name'        => 'Khiên hiệp sĩ',
                'desc'        => 'Tấm khiên danh dự bảo hộ kiên cường của dũng sĩ',
                'price'       => 160,
                'duration'    => 0,
                'animation'   => 'shimmer',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_shield" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#4FACFE"/><stop offset="100%" stop-color="#00F2FE"/></linearGradient></defs><path d="M12 2L3 6v6c0 6.5 4 10 9 11 5-1 9-4.5 9-11V6l-9-4z" fill="url(#znd_g_shield)"/><path d="M12 5v14M7 11h10" stroke="#FFF" stroke-width="1.8" stroke-linecap="round"/></svg>',
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
            'phoenix_fire' => array(
                'id'          => 'phoenix_fire',
                'name'        => 'Phượng hoàng lửa',
                'desc'        => 'Cánh chim phượng hoàng tái sinh từ tro tàn kiêu hãnh và bất tử',
                'price'       => 270,
                'duration'    => 0,
                'animation'   => 'glow',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_phoenix" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#FFD200"/><stop offset="50%" stop-color="#FF7A00"/><stop offset="100%" stop-color="#FF2A6D"/></linearGradient></defs><path d="M12 2c.5 2 2 3.5 3 4-1 .5-2 1.5-2 3 1.5-1 3-1 4.5 0-1 1-1.5 2.5-1 4 2-1 3.5-3 4-5.5.5 3.5-1 6.5-3.5 8.5 2 0 4-1 5-2.5-1 3-3.5 5.5-6.5 6.5 1-1 1.5-2.5 1-4-1 1.5-3 2.5-5 2.5s-4-1-5-2.5c-.5 1.5 0 3 1 4-3-1-5.5-3.5-6.5-6.5 1 1.5 3 2.5 5 2.5-2.5-2-4-5-3.5-8.5.5 2.5 2 4.5 4 5.5.5-1.5 0-3-1-4 1.5-1 3-1 4.5 0 0-1.5-1-2.5-2-3 1-.5 2.5-2 3-4z" fill="url(#znd_g_phoenix)"/><circle cx="12" cy="7" r="1" fill="#FFF"/></svg>',
            ),
            'game_controller' => array(
                'id'          => 'game_controller',
                'name'        => 'Tay cầm Game thủ',
                'desc'        => 'Tay cầm máy chơi game Neon thể thao điện tử eSports cực chất',
                'price'       => 200,
                'duration'    => 0,
                'animation'   => 'pulse',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_game" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#00F2FE"/><stop offset="100%" stop-color="#4FACFE"/></linearGradient></defs><rect x="2" y="6" width="20" height="12" rx="6" fill="url(#znd_g_game)"/><path d="M6 10v4M4 12h4" stroke="#FFF" stroke-width="1.6" stroke-linecap="round"/><circle cx="17.5" cy="10.5" r="1.1" fill="#FF2A6D"/><circle cx="19.5" cy="12.5" r="1.1" fill="#FFE259"/><circle cx="15.5" cy="12.5" r="1.1" fill="#38EF7D"/><circle cx="17.5" cy="14.5" r="1.1" fill="#6C5CE7"/><circle cx="9.5" cy="14" r="1.3" fill="#FFF" opacity="0.8"/><circle cx="13.5" cy="14" r="1.3" fill="#FFF" opacity="0.8"/></svg>',
            ),
            'ninja_shuriken' => array(
                'id'          => 'ninja_shuriken',
                'name'        => 'Phi tiêu Ninja',
                'desc'        => 'Phi tiêu 4 cánh xoay bạc sáng sắc lẹm của sát thủ bóng đêm',
                'price'       => 190,
                'duration'    => 0,
                'animation'   => 'shimmer',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_shuriken" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#CFDEF3"/><stop offset="100%" stop-color="#E0EAFC"/></linearGradient></defs><path d="M12 2l2.5 6.5L21 11l-6.5 2.5L12 20l-2.5-6.5L3 11l6.5-2.5L12 2z" fill="url(#znd_g_shuriken)" stroke="#7F8C8D" stroke-width="0.7"/><circle cx="12" cy="11" r="2.2" fill="#2C3E50"/><circle cx="12" cy="11" r="1" fill="#FFF"/></svg>',
            ),
            'solar_sun' => array(
                'id'          => 'solar_sun',
                'name'        => 'Mặt trời rạng rỡ',
                'desc'        => 'Hào quang mặt trời vàng kim tỏa sáng ấm áp tràn đầy năng lượng',
                'price'       => 180,
                'duration'    => 0,
                'animation'   => 'shimmer',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_sun" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#FFE259"/><stop offset="100%" stop-color="#FFA751"/></linearGradient></defs><circle cx="12" cy="12" r="5" fill="url(#znd_g_sun)" stroke="#FFF" stroke-width="0.8"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.93 4.93l2.12 2.12M16.95 16.95l2.12 2.12M4.93 19.07l2.12-2.12M16.95 7.05l2.12-2.12" stroke="url(#znd_g_sun)" stroke-width="2" stroke-linecap="round"/></svg>',
            ),
            'crystal_ball' => array(
                'id'          => 'crystal_ball',
                'name'        => 'Quả cầu pha lê',
                'desc'        => 'Quả cầu tiên tri ma thuật tím pha lê huyền ảo thấu thị tương lai',
                'price'       => 230,
                'duration'    => 0,
                'animation'   => 'glow',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_crystal" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#E0C3FC"/><stop offset="100%" stop-color="#8EC5FC"/></linearGradient></defs><circle cx="12" cy="10" r="7.5" fill="url(#znd_g_crystal)" stroke="#FFF" stroke-width="0.8"/><path d="M9 10l.7-1.5L11 8l-1.3-.5L9 6l-.5 1.5L7 8l1.5.5L9 10zm6 3l.4-1 1-.4-1-.4-.4-1-.4 1-1 .4 1 .4.4 1z" fill="#FFF"/><path d="M7 19h10l-1.5-2.5h-7L7 19zm2 2h6" stroke="#9B51E0" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            ),
            'coffee_cup' => array(
                'id'          => 'coffee_cup',
                'name'        => 'Tách cà phê Chill',
                'desc'        => 'Tách cà phê bốc khói ấm cúng của những cú đêm làm việc sáng tạo',
                'price'       => 130,
                'duration'    => 0,
                'animation'   => 'float',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_coffee" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#E65C00"/><stop offset="100%" stop-color="#F9D423"/></linearGradient></defs><path d="M4 8h12v7a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V8zm12 2h2a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-2v-5z" fill="url(#znd_g_coffee)"/><path d="M2 20h17" stroke="url(#znd_g_coffee)" stroke-width="2" stroke-linecap="round"/><path d="M7 3c0 1.5 1 2 1 3M11 2c0 1.5 1 2 1 3M15 3c0 1.5 1 2 1 3" stroke="#FFA751" stroke-width="1.4" stroke-linecap="round"/></svg>',
            ),
            'shooting_star' => array(
                'id'          => 'shooting_star',
                'name'        => 'Sao băng ước nguyện',
                'desc'        => 'Ngôi sao băng vút qua bầu trời đêm mang theo may mắn và hy vọng',
                'price'       => 160,
                'duration'    => 0,
                'animation'   => 'shimmer',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_shoot" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#F6D365"/><stop offset="100%" stop-color="#FDA085"/></linearGradient></defs><polygon points="7,2 8.7,5.8 12.8,6.2 9.7,8.9 10.6,12.9 7,10.8 3.4,12.9 4.3,8.9 1.2,6.2 5.3,5.8" fill="url(#znd_g_shoot)"/><path d="M11 6l11 1M10 9l12 1.5M8 12l14 2" stroke="url(#znd_g_shoot)" stroke-width="1.6" stroke-linecap="round" opacity="0.85"/></svg>',
            ),
            'vip_medal' => array(
                'id'          => 'vip_medal',
                'name'        => 'Huy chương danh vọng',
                'desc'        => 'Huy chương vàng ngôi sao danh giá vinh danh những thành viên cống hiến',
                'price'       => 260,
                'duration'    => 0,
                'animation'   => 'shimmer',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><path d="M7 2l5 7-3 1-5-6 3-2zm10 0l-5 7 3 1 5-6-3-2z" fill="#4facfe"/><circle cx="12" cy="15" r="6" fill="#F7971E" stroke="#FFD200" stroke-width="1.5"/><polygon points="12,11.5 13.2,14 16,14.3 13.9,16.1 14.5,18.8 12,17.4 9.5,18.8 10.1,16.1 8,14.3 10.8,14" fill="#FFF"/></svg>',
            ),
            'cyber_skull' => array(
                'id'          => 'cyber_skull',
                'name'        => 'Đầu lâu Cyber Neon',
                'desc'        => 'Đầu lâu công nghệ cyberpunk cá tính, độc lạ và phong cách nổi loạn',
                'price'       => 220,
                'duration'    => 0,
                'animation'   => 'glow',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_skull" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#F72585"/><stop offset="100%" stop-color="#7209B7"/></linearGradient></defs><path d="M12 2a8 8 0 0 0-8 8c0 3 1.5 5.5 4 7v3h8v-3c2.5-1.5 4-4 4-7a8 8 0 0 0-8-8z" fill="url(#znd_g_skull)"/><circle cx="9" cy="10.5" r="2" fill="#00F2FE"/><circle cx="15" cy="10.5" r="2" fill="#00F2FE"/><path d="M11 14h2M10 18v2M12 18v2M14 18v2" stroke="#FFF" stroke-width="1.3" stroke-linecap="round"/></svg>',
            ),
            'golden_bell' => array(
                'id'          => 'golden_bell',
                'name'        => 'Chuông vàng may mắn',
                'desc'        => 'Chiếc chuông vàng phong thủy ngân vang tài lộc bình an',
                'price'       => 150,
                'duration'    => 0,
                'animation'   => 'pulse',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_bell" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#FFE259"/><stop offset="100%" stop-color="#FFA751"/></linearGradient></defs><path d="M12 2a2 2 0 0 0-2 2c0 .3 0 .7.2 1C6.7 6.2 5 9.4 5 13v4l-2 2v1h18v-1l-2-2v-4c0-3.6-1.7-6.8-5.2-8-.1-.3-.1-.7-.1-1a2 2 0 0 0-2-2z" fill="url(#znd_g_bell)"/><circle cx="12" cy="21.5" r="2" fill="#E65100"/><path d="M8 8a5 5 0 0 1 5-4" stroke="#FFF" stroke-width="1" stroke-linecap="round"/></svg>',
            ),
            'moon_crescent' => array(
                'id'          => 'moon_crescent',
                'name'        => 'Trăng khuyết huyền bí',
                'desc'        => 'Vầng trăng lưỡi liềm vàng xanh ôm lấy ngôi sao lung linh đêm thanh vắng',
                'price'       => 170,
                'duration'    => 0,
                'animation'   => 'float',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_moon" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#00C6FF"/><stop offset="100%" stop-color="#0072FF"/></linearGradient></defs><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" fill="url(#znd_g_moon)" stroke="#FFF" stroke-width="0.8"/><polygon points="16,5 17,7.5 19.5,7.8 17.5,9.5 18,12 16,10.6 14,12 14.5,9.5 12.5,7.8 15,7.5" fill="#FFE259"/></svg>',
            ),
            'bubble_tea' => array(
                'id'          => 'bubble_tea',
                'name'        => 'Trà sữa trân châu',
                'desc'        => 'Ly trà sữa trân châu ngọt ngào dễ thương được giới trẻ yêu thích',
                'price'       => 120,
                'duration'    => 0,
                'animation'   => 'pulse',
                'vip_level'   => 0,
                'status'      => true,
                'svg'         => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="znd-svg"><defs><linearGradient id="znd_g_btea" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#FBC2EB"/><stop offset="100%" stop-color="#A6C1EE"/></linearGradient></defs><path d="M5 8h14l-2 13H7L5 8z" fill="url(#znd_g_btea)"/><path d="M4 8h16M13 2l-2 6" stroke="#FA709A" stroke-width="2" stroke-linecap="round"/><circle cx="9" cy="18" r="1.3" fill="#333"/><circle cx="12" cy="18" r="1.3" fill="#333"/><circle cx="15" cy="18" r="1.3" fill="#333"/><circle cx="10.5" cy="15.5" r="1.3" fill="#333"/><circle cx="13.5" cy="15.5" r="1.3" fill="#333"/></svg>',
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
        return $cached;
    }

    /**
     * Danh sách các màu nick tạo sẵn siêu đẹp với Gradient & Glow (tránh màu đỏ & xanh lá của BQT)
     */
    public static function get_default_colors() {
        static $cached = null;
        if ($cached !== null) {
            return $cached;
        }
        $cached = array(
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
            'color_ice_crystal' => array(
                'id'          => 'color_ice_crystal',
                'name'        => 'Băng tinh khiết',
                'desc'        => 'Sắc xanh băng pha lê trắng phát sáng thanh tao và thuần khiết',
                'price'       => 210,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #A8EDEA 0%, #89F7FE 50%, #66A6FF 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(102, 166, 255, 0.2);',
                'animation'   => 'shimmer',
                'glow_color'  => 'rgba(137, 247, 254, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_platinum' => array(
                'id'          => 'color_platinum',
                'name'        => 'Bạch kim xà cừ',
                'desc'        => 'Ánh bạc xà cừ lấp lánh phản chiếu hologram quý phái',
                'price'       => 250,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #E0EAFC 0%, #CFDEF3 50%, #93A5CF 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(147, 165, 207, 0.2);',
                'animation'   => 'shimmer',
                'glow_color'  => 'rgba(224, 234, 252, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_amethyst' => array(
                'id'          => 'color_amethyst',
                'name'        => 'Thạch anh tím',
                'desc'        => 'Tím đá quý thạch anh hoàng gia lung linh huyền diệu',
                'price'       => 230,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #9B51E0 0%, #BB6BD9 50%, #8E2DE2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(155, 81, 224, 0.2);',
                'animation'   => 'glow',
                'glow_color'  => 'rgba(155, 81, 224, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_honey_amber' => array(
                'id'          => 'color_honey_amber',
                'name'        => 'Hổ phách mật ong',
                'desc'        => 'Sắc vàng hổ phách ngọt ngào ấm áp tỏa ánh hào quang',
                'price'       => 180,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #F7971E 0%, #FFD200 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(247, 151, 30, 0.2);',
                'animation'   => 'pulse',
                'glow_color'  => 'rgba(255, 210, 0, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_cotton_candy' => array(
                'id'          => 'color_cotton_candy',
                'name'        => 'Kẹo bông pastel',
                'desc'        => 'Pha trộn ngọt ngào giữa hồng kẹo bông và xanh mộng mơ',
                'price'       => 160,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #FBC2EB 0%, #A6C1EE 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;',
                'animation'   => 'none',
                'glow_color'  => 'rgba(251, 194, 235, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_deep_abyss' => array(
                'id'          => 'color_deep_abyss',
                'name'        => 'Xanh biển sâu',
                'desc'        => 'Xanh lam coban ngọc bích sâu thẳm kiêu sa',
                'price'       => 190,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;',
                'animation'   => 'glow',
                'glow_color'  => 'rgba(33, 147, 176, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_lavender_dream' => array(
                'id'          => 'color_lavender_dream',
                'name'        => 'Giấc mơ oải hương',
                'desc'        => 'Tím oải hương ngát hương thanh nhã và thi vị',
                'price'       => 170,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #9795EF 0%, #F9C5D1 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;',
                'animation'   => 'none',
                'glow_color'  => 'rgba(151, 149, 239, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_aurora' => array(
                'id'          => 'color_aurora',
                'name'        => 'Cực quang Bắc cực',
                'desc'        => 'Dải ánh sáng huyền diệu hòa quyện giữa tím oải hương và xanh cyan phát sáng',
                'price'       => 260,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #7F00FF 0%, #E100FF 40%, #00FFFF 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(127, 0, 255, 0.2);',
                'animation'   => 'glow',
                'glow_color'  => 'rgba(127, 0, 255, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_caramel' => array(
                'id'          => 'color_caramel',
                'name'        => 'Caramel Macchiato',
                'desc'        => 'Màu nâu đồng caramel ấm cúng, sang trọng và thanh lịch của tách cà phê thơm ngát',
                'price'       => 170,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #E65C00 0%, #F9D423 50%, #C79036 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(230, 92, 0, 0.2);',
                'animation'   => 'shimmer',
                'glow_color'  => 'rgba(230, 92, 0, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_rose_gold' => array(
                'id'          => 'color_rose_gold',
                'name'        => 'Vàng hồng Rose Gold',
                'desc'        => 'Sắc vàng hồng ánh kim nữ tính, kiêu sa, quyến rũ và đắt giá',
                'price'       => 240,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #F3A183 0%, #ECA1AC 50%, #F1828D 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(243, 161, 131, 0.2);',
                'animation'   => 'shimmer',
                'glow_color'  => 'rgba(241, 130, 141, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_twilight' => array(
                'id'          => 'color_twilight',
                'name'        => 'Hoàng hôn tím Twilight',
                'desc'        => 'Sắc tím thẫm hòa quyện xanh lam đêm sâu thẳm lãng mạn',
                'price'       => 200,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #5B247A 0%, #1BCEDF 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(91, 36, 122, 0.2);',
                'animation'   => 'glow',
                'glow_color'  => 'rgba(91, 36, 122, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_champagne' => array(
                'id'          => 'color_champagne',
                'name'        => 'Rượu vang Champagne',
                'desc'        => 'Sắc vàng champagne óng ánh của những bữa tiệc thượng lưu danh giá',
                'price'       => 230,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #F5D061 0%, #E6CA65 50%, #F3E7BE 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(245, 208, 97, 0.2);',
                'animation'   => 'shimmer',
                'glow_color'  => 'rgba(245, 208, 97, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_citrus' => array(
                'id'          => 'color_citrus',
                'name'        => 'Cam san hô Citrus',
                'desc'        => 'Cam san hô rực sáng ấm áp tràn đầy sức sống và nhiệt huyết tuổi trẻ',
                'price'       => 160,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #FF512F 0%, #F09819 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(255, 81, 47, 0.2);',
                'animation'   => 'pulse',
                'glow_color'  => 'rgba(255, 81, 47, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_neon_cyan' => array(
                'id'          => 'color_neon_cyan',
                'name'        => 'Xanh băng Neon Cyan',
                'desc'        => 'Xanh lam neon điện quang cực sáng phong cách tương lai Sci-fi bứt phá',
                'price'       => 220,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #00F2FE 0%, #4FACFE 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(0, 242, 254, 0.2);',
                'animation'   => 'glow',
                'glow_color'  => 'rgba(0, 242, 254, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_mystic_plum' => array(
                'id'          => 'color_mystic_plum',
                'name'        => 'Mận tím huyền thuật',
                'desc'        => 'Sắc tím mận đậm đà quý tộc kiêu hãnh và bí ẩn vô song',
                'price'       => 210,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #8E2DE2 0%, #4A00E0 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(142, 45, 226, 0.2);',
                'animation'   => 'shimmer',
                'glow_color'  => 'rgba(142, 45, 226, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_midnight_blue' => array(
                'id'          => 'color_midnight_blue',
                'name'        => 'Đêm sâu Midnight Blue',
                'desc'        => 'Xanh đen vũ trụ rải ánh ngọc bích huyền bí của màn đêm tĩnh lặng',
                'price'       => 190,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #0F2027 0%, #203A43 50%, #2C5364 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(44, 83, 100, 0.2);',
                'animation'   => 'shimmer',
                'glow_color'  => 'rgba(44, 83, 100, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_coral_peach' => array(
                'id'          => 'color_coral_peach',
                'name'        => 'Đào san hô ngọc trai',
                'desc'        => 'Hồng cam đào san hô mềm mại, dễ thương, tươi tắn và trong trẻo',
                'price'       => 150,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #FF9A8B 0%, #FF6A88 55%, #FF99AC 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(255, 106, 136, 0.2);',
                'animation'   => 'none',
                'glow_color'  => 'rgba(255, 106, 136, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_titanium' => array(
                'id'          => 'color_titanium',
                'name'        => 'Kim loại Titanium',
                'desc'        => 'Xám bạc titan ánh thép đanh thép, rắn rỏi và đẳng cấp công nghệ',
                'price'       => 240,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #B7B8B9 0%, #E8E9EA 50%, #7C7D7E 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(183, 184, 185, 0.2);',
                'animation'   => 'shimmer',
                'glow_color'  => 'rgba(183, 184, 185, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
            'color_sweet_candy' => array(
                'id'          => 'color_sweet_candy',
                'name'        => 'Kẹo ngọt Bubblegum',
                'desc'        => 'Hồng phấn kẹo ngọt phối sữa dâu pastel mộng mơ đáng yêu',
                'price'       => 140,
                'duration'    => 0,
                'css_style'   => 'background: linear-gradient(135deg, #EE9CA7 0%, #FFDDE1 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; text-shadow: 0 1px 2px rgba(238, 156, 167, 0.2);',
                'animation'   => 'pulse',
                'glow_color'  => 'rgba(238, 156, 167, 0.25)',
                'vip_level'   => 0,
                'status'      => true,
            ),
        );
        return $cached;
    }

    /**
     * Lấy danh sách icon hiện hành (từ cấu hình theme hoặc mặc định)
     */
    public static function get_all_icons() {
        static $cached = null;
        if ($cached !== null) {
            return $cached;
        }
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
                    } else {
                        unset($item['role_exclusive']);
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

        $cached = !empty($result) ? $result : $defaults;
        return $cached;
    }

    /**
     * Lấy danh sách màu hiện hành (từ cấu hình theme hoặc mặc định)
     */
    public static function get_all_colors() {
        static $cached = null;
        if ($cached !== null) {
            return $cached;
        }
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
                // Loại bỏ màu đỏ và xanh lá cũ để tránh trùng nhận diện với Ban Quản Trị
                if ($id === 'color_fire_red' || $id === 'color_neon_mint') {
                    continue;
                }
                if (!empty($item['css_style'])) {
                    // Tự động làm sạch bóng mờ text-shadow cũ quá dày (10px, 12px...) xuống mức thanh mảnh 1px 2px
                    $item['css_style'] = preg_replace('/text-shadow:\s*0\s+0\s+(?:10|12|\d{2,})px\s+rgba\(([^,\)]+),([^,\)]+),([^,\)]+),\s*([0-9.]+)\);?/i', 'text-shadow: 0 1px 2px rgba($1,$2,$3,0.2);', $item['css_style']);
                }
                if (isset($defaults[$id]['role_exclusive'])) {
                    $item['role_exclusive'] = $defaults[$id]['role_exclusive'];
                } else {
                    unset($item['role_exclusive']);
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

        $cached = !empty($result) ? $result : $defaults;
        return $cached;
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

        static $cached_multipliers = null;
        if ($cached_multipliers === null) {
            $m7  = 10;
            $m30 = 20;
            $mf  = 30;
            if (function_exists('_pz')) {
                $v7  = (int)_pz('znd_price_multiplier_7', 10);
                $v30 = (int)_pz('znd_price_multiplier_30', 20);
                $vf  = (int)_pz('znd_price_multiplier_forever', 30);
                if ($v7 > 0)  $m7  = $v7;
                if ($v30 > 0) $m30 = $v30;
                if ($vf > 0)  $mf  = $vf;
            }
            $cached_multipliers = array($m7, $m30, $mf);
        }
        list($multiplier_7, $multiplier_30, $multiplier_forever) = $cached_multipliers;

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
        static $cache = array();
        if (isset($cache[$user_id])) {
            return $cache[$user_id];
        }
        $cache[$user_id] = is_super_admin($user_id) || user_can($user_id, 'manage_options');
        return $cache[$user_id];
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
        static $cache = array();
        if (isset($cache[$user_id])) {
            return $cache[$user_id];
        }
        // Nếu đã là admin thì không tính là mod (admin là cấp cao nhất)
        if (self::is_user_admin($user_id)) {
            $cache[$user_id] = false;
            return false;
        }
        // Kiểm tra quyền kiểm duyệt hoặc chỉnh sửa
        if (user_can($user_id, 'moderate_comments') || user_can($user_id, 'edit_others_posts')) {
            $cache[$user_id] = true;
            return true;
        }
        $user = get_userdata($user_id);
        if ($user && !empty($user->roles)) {
            $mod_roles = array('editor', 'moderator', 'bbp_moderator', 'zib_moderator');
            if (array_intersect($mod_roles, (array)$user->roles)) {
                $cache[$user_id] = true;
                return true;
            }
        }
        $cache[$user_id] = false;
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
        static $cache = array();
        if (isset($cache[$user_id])) {
            return $cache[$user_id];
        }
        // 1. Kiểm tra qua hàm zib_user_is_ban của Theme Zibll
        if (function_exists('zib_user_is_ban') && zib_user_is_ban($user_id)) {
            $cache[$user_id] = true;
            return true;
        }
        // 2. Kiểm tra trực tiếp qua user meta của Theme Zibll
        $banned = get_user_meta($user_id, 'banned', true);
        if (!empty($banned)) {
            $ban_time = get_user_meta($user_id, 'banned_time', true);
            if ($ban_time && strtotime($ban_time) < time()) {
                $cache[$user_id] = false;
                return false;
            }
            $cache[$user_id] = true;
            return true;
        }
        $cache[$user_id] = false;
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

