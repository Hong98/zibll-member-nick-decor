/**
 * Zibll Member Nickname & Badge Pro - Frontend Controller
 * Tuân thủ quy tắc modal-backdrop-close (mousedown & mouseup trên backdrop)
 */

(function ($) {
    'use strict';

    var ZND = {
        // Kiểm tra xem người dùng đã đăng nhập hay chưa (hỗ trợ cả khi bật cache HTML)
        isUserLoggedIn: function () {
            if (typeof znd_vars !== 'undefined' && (znd_vars.is_logged_in === true || znd_vars.is_logged_in === 1 || znd_vars.is_logged_in === '1')) {
                return true;
            }
            if ($('body').hasClass('logged-in')) {
                return true;
            }
            return false;
        },

        init: function () {
            this.bindEvents();
            this.autoExpandIconContainers();
            this.fixMobileNavbarSvgIds();
            this.applyUserPageDecor();

            var self = this;
            [100, 300, 800, 1500].forEach(function (ms) {
                setTimeout(function () {
                    self.fixMobileNavbarSvgIds();
                    self.applyUserPageDecor();
                }, ms);
            });

            // CHỈ hiển thị biểu tượng mở Shop khi người dùng đã đăng nhập (PC & di động)
            if (this.isUserLoggedIn()) {
                this.cleanHeaderTooltip();
                this.injectUserMenuButton();
                this.injectMobileButtons();
            } else {
                // Nếu chưa đăng nhập, dọn dẹp sạch toàn bộ các icon/nút mở shop trên giao diện (phòng ngừa page cache)
                $('.open-nick-decor-modal, .znd-user-shortcuts-section, .znd-mobile-header-btn, .znd-mobile-drawer-btn').remove();
            }
        },

        // Chuẩn hoá và cô lập ID gradient duy nhất khi render SVG động phía client
        prepareSvg: function (svgHtml) {
            if (!svgHtml || typeof svgHtml !== 'string') return svgHtml || '';
            var uid = 'js_' + Math.random().toString(36).substring(2, 9);
            return svgHtml.replace(/\b(id=["'])(znd_g_[^"']+)(["'])/gi, function (m, p1, p2, p3) {
                return p1 + p2 + '_' + uid + p3;
            }).replace(/\b(url\(#)(znd_g_[^)\s]+)(\))/gi, function (m, p1, p2, p3) {
                return p1 + p2 + '_' + uid + p3;
            });
        },

        // Khắc phục triệt để lỗi Theme Zibll dùng static $html khiến icon trong mobile-navbar bị trùng ID gradient với desktop header (bị display:none)
        fixMobileNavbarSvgIds: function () {
            $('.mobile-navbar .znd-nick-icon svg').each(function () {
                var $svg = $(this);
                var html = $svg.prop('outerHTML');
                if (html && html.indexOf('znd_g_') !== -1) {
                    var mobId = 'mob_' + Math.random().toString(36).substring(2, 8);
                    var newHtml = html.replace(/\b(id=["'])(znd_g_[^"']+)(["'])/gi, function (m, p1, p2, p3) {
                        return p1 + p2 + '_' + mobId + p3;
                    }).replace(/\b(url\(#)(znd_g_[^)\s]+)(\))/gi, function (m, p1, p2, p3) {
                        return p1 + p2 + '_' + mobId + p3;
                    });
                    $svg.replaceWith(newHtml);
                }
            });
        },

        // Tự động chèn icon và màu vào Header Trang cá nhân / Trung tâm người dùng (/user)
        applyUserPageDecor: function () {
            var self = this;
            if (!self.isUserLoggedIn() || typeof znd_vars === 'undefined') return;

            var iconHtml   = znd_vars.user_icon_html || '';
            var colorClass = znd_vars.user_color_class || '';

            if (!iconHtml && !colorClass) return;

            // Tìm thẻ tên trong khối author-header của trang cá nhân
            $('.author-header .header-info, .author-header').each(function () {
                var $header = $(this);
                var $displayName = $header.find('.name .display-name, .display-name').first();

                if ($displayName.length) {
                    // Gán class màu nick
                    if (colorClass && !$displayName.hasClass(colorClass)) {
                        $displayName.addClass(colorClass);
                    }

                    // Chèn icon đầu nick nếu chưa có
                    if (iconHtml && !$displayName.find('.znd-nick-icon').length) {
                        $displayName.addClass('has-znd-icon');
                        var $vip = $displayName.find('.mr3, .payvip-icon, [class*="vip"]').first();
                        var freshSvg = self.prepareSvg(iconHtml);
                        if ($vip.length) {
                            $vip.after(freshSvg);
                        } else {
                            $displayName.prepend(freshSvg);
                        }
                    }
                }
            });
        },

        // Tự động kiểm tra và gắn class has-znd-icon cho các container chứa tên có icon nick
        autoExpandIconContainers: function () {
            $('.sub-user-box').each(function () {
                var $box = $(this);
                // Nếu nằm trong menu trượt di động mobile-navbar thì không gắn has-znd-icon để tránh phình to lệch menu
                if ($box.closest('.mobile-navbar').length) {
                    $box.removeClass('has-znd-icon');
                    return;
                }

                if ($box.find('.znd-nick-icon').length) {
                    $box.addClass('has-znd-icon');
                } else {
                    $box.removeClass('has-znd-icon');
                }
            });

            // Gắn class has-znd-icon cho các thẻ name hoặc display-name có icon nick
            $('.znd-nick-icon').each(function () {
                var $icon = $(this);
                $icon.closest('name, .display-name, .comment-header, .user-info').addClass('has-znd-icon');
            });
        },

        // Tự động làm mới số dư hiện tại của thành viên qua AJAX
        refreshUserBalance: function () {
            if (!this.isUserLoggedIn() || typeof znd_vars === 'undefined') return;

            $.ajax({
                url: znd_vars.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'znd_get_user_balance',
                    nonce: znd_vars.nonce
                },
                success: function (res) {
                    if (res && res.success && res.data && typeof res.data.balance !== 'undefined') {
                        var formatted = res.data.balance_formatted || Number(res.data.balance).toLocaleString();
                        $('#znd-current-balance').text(formatted);
                    }
                }
            });
        },

        // Mở Modal (Tích hợp cơ chế Bootstrap Modal của Theme Zibll)
        openModal: function () {
            var $modal = $('#znd-shop-modal');
            if (!$modal.length) return;

            $('body').addClass('znd-modal-open modal-open');
            if ($.fn.modal) {
                $modal.modal({ backdrop: 'static', keyboard: true }).modal('show');
            } else {
                $modal.addClass('in is-open').show();
            }
            $modal.css('display', 'flex');
        },

        // Đóng Modal (Tích hợp cơ chế Bootstrap Modal của Theme Zibll)
        closeModal: function () {
            var $modal = $('#znd-shop-modal');
            if (!$modal.length) return;

            $('body').removeClass('znd-modal-open');
            if ($.fn.modal) {
                $modal.modal('hide');
            } else {
                $modal.removeClass('in is-open').hide();
                $('body').removeClass('modal-open');
            }
            $modal.css('display', 'none');
        },

        confirmCallback: null,

        // Mở popup xác nhận mua vật phẩm chuẩn Theme Zibll (Thay thế hoàn toàn window.confirm)
        openConfirmModal: function (options) {
            var self = this;
            var $modal = $('#znd-confirm-modal');
            if (!$modal.length) {
                var closeIconSvg = '<svg class="ic-close" aria-hidden="true"><use xlink:href="#icon-close"></use></svg>';
                var cartIconSvg  = '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-shopping-cart"></use></svg>';
                var modalHtml = '<div class="modal fade znd-confirm-modal-wrap" id="znd-confirm-modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">' +
                    '<div class="modal-dialog modal-sm" role="document">' +
                    '<div class="modal-content">' +
                    '<div class="modal-body">' +
                    '<div style="padding: 1px;">' +
                    '<div class="modal-colorful-header colorful-bg jb-yellow">' +
                    '<button class="close znd-confirm-cancel" data-dismiss="modal">' + closeIconSvg + '</button>' +
                    '<div class="colorful-make"></div>' +
                    '<div class="text-center">' +
                    '<div class="em2x">' + cartIconSvg + '</div>' +
                    '<div class="mt10 em12 padding-w10 font-bold">Xác nhận mua vật phẩm</div>' +
                    '</div>' +
                    '</div>' +
                    '<div style="padding: 15px 15px 10px;">' +
                    '<div class="znd-confirm-preview mb10" style="min-height: 48px; display: flex; align-items: center; justify-content: center;"></div>' +
                    '<div class="em12 mb6 znd-confirm-title text-center" style="color: var(--focus-color, #ff6f06);"></div>' +
                    '<div class="muted-2-color mb6 em09 text-center">Thời hạn sử dụng: <span class="c-yellow znd-confirm-duration"></span></div>' +
                    '<div class="muted-2-color em09 text-center">Giá thanh toán: <span class="c-red em11 znd-confirm-price"></span></div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="modal-buts but-average">' +
                    '<a type="button" class="but znd-confirm-cancel" href="javascript:;">Hủy bỏ</a>' +
                    '<a type="button" class="but c-yellow znd-confirm-ok" href="javascript:;">Xác nhận mua</a>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                $('body').append(modalHtml);
                $modal = $('#znd-confirm-modal');
            }

            $modal.find('.znd-confirm-title').text(options.name || '');
            $modal.find('.znd-confirm-duration').text(options.durationName || '');
            $modal.find('.znd-confirm-price').text(options.priceText || '');

            var $preview = $modal.find('.znd-confirm-preview');
            if (options.previewHtml) {
                $preview.html(options.previewHtml).show();
            } else {
                $preview.empty().hide();
            }

            self.confirmCallback = options.onConfirm || null;

            $modal.css({
                'z-index': 100060,
                'display': 'flex'
            });

            if ($.fn.modal) {
                $modal.modal({ backdrop: 'static', keyboard: true }).modal('show');
            } else {
                $modal.addClass('in is-open').show();
            }
        },

        // Đóng popup xác nhận mua vật phẩm
        closeConfirmModal: function () {
            var $modal = $('#znd-confirm-modal');
            if (!$modal.length) return;

            if ($.fn.modal) {
                $modal.modal('hide');
            } else {
                $modal.removeClass('in is-open').hide();
            }
            $modal.css('display', 'none');
            this.confirmCallback = null;

            // Luôn đảm bảo giữ nguyên modal Cửa hàng (#znd-shop-modal) mở để thành viên tiếp tục duyệt xem vật phẩm khác
            var $shopModal = $('#znd-shop-modal');
            if ($shopModal.length && ($shopModal.hasClass('in') || $shopModal.is(':visible'))) {
                $shopModal.css('display', 'flex');
                $('body').addClass('znd-modal-open modal-open');
                setTimeout(function () {
                    if ($shopModal.is(':visible') && !$('.modal-backdrop.in, .modal-backdrop.show').length) {
                        $('<div class="modal-backdrop fade in"></div>').appendTo('body');
                    }
                }, 50);
            }
        },

        // Cập nhật giao diện thời gian thực trên toàn trang không cần tải lại (HTMX Reactive DOM Swap)
        updateUserDecorOnPage: function (data) {
            if (!data) return;
            var self = this;

            var iconHtml       = data.user_icon_html || '';
            var colorClass     = data.user_color_class || '';
            var equippedIcon   = data.equipped_icon || '';
            var equippedColor  = data.equipped_color || '';

            // 1. Cập nhật bộ nhớ biến toàn cục
            if (typeof znd_vars !== 'undefined') {
                znd_vars.equipped_icon   = equippedIcon;
                znd_vars.equipped_color  = equippedColor;
                znd_vars.user_icon_html  = iconHtml;
                znd_vars.user_color_class= colorClass;
                if (typeof data.user_balance !== 'undefined') {
                    znd_vars.user_balance = data.user_balance;
                }
            }

            // 2. Cập nhật số dư hiển thị tức thì
            var formattedBal = data.user_balance_formatted || (typeof data.user_balance !== 'undefined' ? Number(data.user_balance).toLocaleString() : null);
            if (formattedBal !== null) {
                $('#znd-current-balance').text(formattedBal);
            }

            // 3. Cập nhật tất cả các vị trí tên người dùng trên trang hiện tại
            var targetSelectors = [
                '.sub-user-box .display-name',
                '.sub-user-box .user-name',
                '.mobile-navbar .sub-user-box .display-name',
                '.mobile-navbar .sub-user-box .user-name',
                '.navbar-user .display-name',
                '.navbar-user .user-name',
                '.header-user-href .display-name',
                '.author-header .header-info .name .display-name',
                '.author-header .display-name',
                '.author-header .name'
            ].join(', ');

            $(targetSelectors).each(function () {
                var $el = $(this);

                // Cập nhật màu nick (Color Class & Animation)
                var rawClass = $el.attr('class') || '';
                var keptClasses = rawClass.split(/\s+/).filter(function (cls) {
                    return cls && !cls.startsWith('znd-color-') && !cls.startsWith('znd-anim-');
                });
                if (colorClass) {
                    keptClasses.push(colorClass);
                }
                $el.attr('class', keptClasses.join(' '));

                // Cập nhật icon đầu nick (SVG)
                $el.find('.znd-nick-icon').remove();

                if (iconHtml) {
                    $el.addClass('has-znd-icon');
                    var freshSvg = self.prepareSvg(iconHtml);
                    var $vip = $el.find('.mr3, .payvip-icon, [class*="vip-"]').first();
                    if ($vip.length) {
                        $vip.after(freshSvg);
                    } else {
                        $el.prepend(freshSvg);
                    }
                } else {
                    $el.removeClass('has-znd-icon');
                }
            });

            // Tự động giãn nở container và sửa ID SVG trên mobile drawer
            self.autoExpandIconContainers();
            self.fixMobileNavbarSvgIds();

            // 4. Cập nhật khung xem trước Live Preview trong Shop / Tủ đồ
            var $wrapper = $('.znd-shop-wrapper');
            if ($wrapper.length) {
                var $pIcon = $wrapper.find('#znd-preview-icon');
                var $pName = $wrapper.find('#znd-preview-name');
                var $pDesc = $wrapper.find('#znd-preview-desc');

                if (iconHtml) {
                    $pIcon.html(self.prepareSvg(iconHtml));
                } else {
                    $pIcon.empty();
                }

                if ($pName.length) {
                    var pClasses = ($pName.attr('class') || '').split(/\s+/).filter(function (c) {
                        return c && !c.startsWith('znd-color-') && !c.startsWith('znd-anim-');
                    });
                    if (colorClass) {
                        pClasses.push(colorClass);
                    }
                    $pName.attr('class', pClasses.join(' '));
                }

                $pDesc.text('Đang dùng');
            }

            // 4.1. Cập nhật nội dung tab Tủ đồ thời gian thực qua AJAX (HTMX / Reactive DOM Swap)
            if (typeof data.wardrobe_html !== 'undefined' && data.wardrobe_html) {
                var $wardrobeTab = $('#znd-tab-wardrobe');
                if ($wardrobeTab.length) {
                    $wardrobeTab.html(data.wardrobe_html);
                }
            }

            // 4.2. Cập nhật số lượng vật phẩm hiển thị ở badge tab Tủ đồ
            var ownedCount = (typeof data.owned_count !== 'undefined') ? data.owned_count : null;
            if (ownedCount === null && typeof data.wardrobe_html !== 'undefined' && data.wardrobe_html) {
                var $wTab = $('#znd-tab-wardrobe');
                if ($wTab.length) {
                    ownedCount = $wTab.find('.znd-card').length;
                }
            }
            if (ownedCount !== null) {
                $('.znd-owned-count').text(ownedCount);
            }

            // 5. Kích hoạt Custom Event cho các script khác nếu có
            $(document).trigger('znd:decorChanged', [data]);
        },

        // Thông báo qua hệ thống notyf chuẩn của Theme Zibll kết hợp Toast banner nổi bật
        showToast: function (message, type) {
            type = type || 'success';
            var notyfType = (type === 'success') ? 'success' : (type === 'error' ? 'danger' : type);

            // Gọi hàm notyf của Zibll nếu có
            try {
                if (typeof window.notyf === 'function') {
                    window.notyf(message, notyfType);
                } else if (typeof notyf === 'function') {
                    notyf(message, notyfType);
                }
            } catch (e) {}

            // Hiển thị toast nổi bật trên cùng giữa màn hình
            var bg = (type === 'success') ? '#12b928' : '#ff5473';
            var icon = (type === 'success') ? 'fa-check-circle' : 'fa-exclamation-circle';

            $('.znd-toast-msg').remove();
            var $toast = $('<div class="znd-toast-msg" style="position:fixed;top:30px;left:50%;transform:translateX(-50%);z-index:99999999;background:' + bg + ';color:#fff;padding:12px 24px;border-radius:50px;font-size:14px;font-weight:500;box-shadow:0 8px 30px rgba(0,0,0,0.35);display:flex;align-items:center;gap:10px;pointer-events:none;animation:znd-fade-in 0.3s ease;"><i class="fa ' + icon + '"></i> ' + message + '</div>');
            $('body').append($toast);

            setTimeout(function () {
                $toast.fadeOut(400, function () {
                    $(this).remove();
                });
            }, 3500);
        },

        // Đồng bộ tooltip icon mở shop trên navbar theo chuẩn Theme Zibll (bỏ bóng mây Bootstrap bị khuất phía trên)
        cleanHeaderTooltip: function () {
            try {
                var fixTooltip = function () {
                    var $btn = $('.navbar .open-nick-decor-modal, .header .open-nick-decor-modal');
                    if ($btn.length) {
                        $btn.removeAttr('data-toggle');
                        if ($.fn.tooltip) {
                            $btn.tooltip('destroy');
                        }
                    }
                };
                fixTooltip();
                $(document).ready(fixTooltip);
                setTimeout(fixTooltip, 300);
            } catch (e) {
                console.warn('ZND cleanHeaderTooltip error:', e);
            }
        },

        // Tự động chèn một hàng mới dưới cùng trong User Dropdown Box Theme Zibll (giữ nguyên 4 icon/hàng)
        injectUserMenuButton: function () {
            var self = this;
            if (!self.isUserLoggedIn()) {
                $('.znd-user-shortcuts-section, .znd-user-href-btn').remove();
                return;
            }

            try {
                var sectionHtml = '<div class="znd-user-shortcuts-section mt10 translate-ignore notranslate">' +
                                  '<div class="relative opacity5 mb10"><i class="line-form-line"></i></div>' +
                                  '<div class="znd-user-row">' +
                                  '<a href="javascript:;" data-znd-action="modal" class="open-nick-decor-modal znd-user-href-btn" title="Cửa hàng màu & Icon nick">' +
                                  '<div class="badg mb6 toggle-radius c-yellow"><i class="fa fa-paint-brush"></i></div>' +
                                  '<div class="c-yellow translate-ignore">Màu & Icon</div>' +
                                  '</a>' +
                                  '</div>' +
                                  '</div>';

                var isAdjusting = false;
                var ensureAtBottom = function () {
                    if (isAdjusting) return;
                    if (!self.isUserLoggedIn()) {
                        $('.znd-user-shortcuts-section, .znd-user-href-btn').remove();
                        return;
                    }
                    isAdjusting = true;
                    try {
                        // Xóa các nút thừa / rác nếu có trong các hàng 4 icon hiện tại
                        $('.header-user-href .znd-user-href-btn').remove();
                        $('.header-user-href').removeClass('has-znd-btn');
                        $('.znd-user-menu-bottom').remove();

                        var $subUserBoxes = $('.user-name .sub-user-box, .navbar .sub-user-box, .mobile-navbar .sub-user-box, .sub-user-box');

                        $subUserBoxes.each(function () {
                            var $box = $(this);
                            var $center = $box.find('.text-center').first();
                            if (!$center.length) {
                                $center = $box;
                            }

                            // Nếu zac-admin-shortcuts-section bị kẹt bên trong znd-user-shortcuts-section, giải phóng nó ra container
                            var $nestedZac = $box.find('.znd-user-shortcuts-section .zac-admin-shortcuts-section');
                            if ($nestedZac.length) {
                                $center.append($nestedZac);
                            }

                            var $zac = $box.find('.zac-admin-shortcuts-section').first();
                            var $znd = $box.find('.znd-user-shortcuts-section').first();

                            if (!$znd.length) {
                                $znd = $(sectionHtml);
                            } else {
                                // Tuyệt đối không để class header-user-href trong znd để tránh zac nhận nhầm là last header-user-href
                                $znd.find('.header-user-href').removeClass('header-user-href');
                            }

                            // Nếu có hàng phím tắt quản trị zac, hàng Màu & Icon BẮT BUỘC phải nằm ngay sau nó (ở dưới cùng)
                            if ($zac.length) {
                                if ($zac.next('.znd-user-shortcuts-section')[0] !== $znd[0]) {
                                    $zac.after($znd);
                                }
                            } else {
                                // Nếu chưa có zac, hàng Màu & Icon nằm ở cuối cùng của container
                                if ($center.children().last()[0] !== $znd[0]) {
                                    $center.append($znd);
                                }
                            }
                        });

                        // Cập nhật co giãn độ rộng cho .sub-user-box nếu có icon nick
                        self.autoExpandIconContainers();
                    } finally {
                        isAdjusting = false;
                    }
                };

                // Chạy ngay khi DOM sẵn sàng
                ensureAtBottom();

                // Chạy định kỳ ở các mốc thời gian đón đầu zac-frontend.js hoặc các plugin khác render muộn
                [30, 80, 150, 300, 600, 1000, 2000].forEach(function (ms) {
                    setTimeout(ensureAtBottom, ms);
                });

                // Lắng nghe tương tác chuột khi hover hoặc click vào menu người dùng
                $(document).on('mouseenter mouseover show.bs.dropdown click', '.navbar-avatar, .user-name, .sub-user-box, [data-toggle="dropdown"]', function () {
                    ensureAtBottom();
                });

                // Sử dụng MutationObserver để tự động định vị lại ngay tức thì khi DOM của .sub-user-box biến động
                if (window.MutationObserver) {
                    var observerTimeout = null;
                    var observer = new MutationObserver(function () {
                        if (isAdjusting) return;
                        clearTimeout(observerTimeout);
                        observerTimeout = setTimeout(ensureAtBottom, 20);
                    });

                    $('.sub-user-box').each(function () {
                        observer.observe(this, { childList: true, subtree: true });
                    });
                }
            } catch (e) {
                console.warn('ZND injectUserMenuButton error:', e);
            }
        },

        // Bổ sung icon mở shop trên thiết bị di động (Mobile Header & Mobile Drawer)
        injectMobileButtons: function () {
            var self = this;
            if (!self.isUserLoggedIn()) {
                $('.znd-mobile-header-btn, .znd-mobile-drawer-btn').remove();
                return;
            }

            try {
                var titleText = 'Cửa hàng màu & Icon nick';
                var isOptionEnabled = function (val, defaultVal) {
                    if (typeof val === 'undefined') return defaultVal !== false;
                    return val === true || val === 1 || val === '1' || val === 'true';
                };

                var enableMobileHeader = (typeof znd_vars !== 'undefined') ? isOptionEnabled(znd_vars.enable_mobile_header_btn, true) : true;
                var enableMobileDrawer = (typeof znd_vars !== 'undefined') ? isOptionEnabled(znd_vars.enable_mobile_drawer_btn, true) : true;

                var insertMobile = function () {
                    if (!self.isUserLoggedIn()) {
                        $('.znd-mobile-header-btn, .znd-mobile-drawer-btn').remove();
                        return;
                    }

                    // 1. Mobile Header bar (.navbar-header)
                    if (enableMobileHeader) {
                        var $navbarHeader = $('.navbar-header');
                        if ($navbarHeader.length && !$navbarHeader.find('.znd-mobile-header-btn').length) {
                            var $mBtn = $(
                                '<a href="javascript:;" data-znd-action="modal" class="navbar-toggle open-nick-decor-modal znd-mobile-header-btn visible-xs" title="' + titleText + '">' +
                                '<i class="fa fa-paint-brush" aria-hidden="true"></i>' +
                                '</a>'
                            );

                            var $msgBtn = $navbarHeader.find('.zac-mobile-header-msg-btn');
                            var $searchBtn = $navbarHeader.find('.main-search-btn.navbar-toggle');
                            var $menuToggle = $navbarHeader.find('button[data-toggle-class="mobile-navbar-show"]');

                            if ($msgBtn.length) {
                                $msgBtn.after($mBtn);
                            } else if ($searchBtn.length) {
                                $searchBtn.after($mBtn);
                            } else if ($menuToggle.length) {
                                $menuToggle.before($mBtn);
                            } else {
                                $navbarHeader.append($mBtn);
                            }
                        }
                    } else {
                        $('.navbar-header .znd-mobile-header-btn, .znd-mobile-header-btn').remove();
                    }

                    // 2. Mobile Drawer Top Header (.mobile-navbar .flex.ac.pull-right)
                    if (enableMobileDrawer) {
                        var $drawerTop = $('.mobile-navbar .flex.ac.pull-right');
                        if ($drawerTop.length && !$drawerTop.find('.znd-mobile-drawer-btn').length) {
                            var $drawerBtn = $(
                                '<a href="javascript:;" data-znd-action="modal" class="toggle-radius open-nick-decor-modal znd-mobile-drawer-btn" title="' + titleText + '">' +
                                '<i class="fa fa-paint-brush"></i>' +
                                '</a>'
                            );
                            $drawerTop.prepend($drawerBtn);
                        }
                    } else {
                        $('.mobile-navbar .znd-mobile-drawer-btn, .znd-mobile-drawer-btn').remove();
                    }
                };

                insertMobile();
                $(document).ready(insertMobile);
                [50, 150, 300, 600, 1200, 2500].forEach(function (ms) {
                    setTimeout(insertMobile, ms);
                });
            } catch (e) {
                console.warn('ZND injectMobileButtons error:', e);
            }
        },

        // Helper sinh nút thao tác cho Action Drawer
        getDrawerBtnHtml: function (type, itemId, status, priceText, duration, exclusive) {
            duration = (typeof duration !== 'undefined') ? duration : 7;
            exclusive = exclusive || '';

            // Nếu người dùng đang bị khóa
            if (typeof znd_vars !== 'undefined' && znd_vars.is_banned) {
                return '<button type="button" class="but but-sm c-red radius disabled znd-btn-exclusive-disabled" disabled><i class="fa fa-lock mr3"></i> Tài khoản đang bị khóa</button>';
            }

            // Nếu là item đặc quyền Admin
            if (exclusive === 'admin') {
                if (typeof znd_vars !== 'undefined' && znd_vars.is_admin) {
                    if (status === 'equipped') {
                        return '<button type="button" class="but but-sm c-green radius znd-btn znd-btn-unequip" data-znd-act="unequip" data-type="' + type + '" data-id="' + itemId + '"><i class="fa fa-check-circle mr3"></i> Đang dùng (Tháo)</button>';
                    } else {
                        return '<button type="button" class="but but-sm c-blue radius znd-btn znd-btn-equip" data-znd-act="equip" data-type="' + type + '" data-id="' + itemId + '"><i class="fa fa-bolt mr3"></i> Trang bị (Admin)</button>';
                    }
                } else {
                    return '<button type="button" class="but but-sm c-red radius disabled znd-btn-exclusive-disabled" disabled><i class="fa fa-lock mr3"></i> Đặc quyền Quản trị viên</button>';
                }
            }

            // Nếu là item đặc quyền Điều hành viên (Mod/Editor)
            if (exclusive === 'mod') {
                if (typeof znd_vars !== 'undefined' && znd_vars.is_mod) {
                    if (status === 'equipped') {
                        return '<button type="button" class="but but-sm c-green radius znd-btn znd-btn-unequip" data-znd-act="unequip" data-type="' + type + '" data-id="' + itemId + '"><i class="fa fa-check-circle mr3"></i> Đang dùng (Tháo)</button>';
                    } else {
                        return '<button type="button" class="but but-sm c-blue radius znd-btn znd-btn-equip" data-znd-act="equip" data-type="' + type + '" data-id="' + itemId + '"><i class="fa fa-bolt mr3"></i> Trang bị (Điều hành)</button>';
                    }
                } else {
                    return '<button type="button" class="but but-sm c-green radius disabled znd-btn-exclusive-disabled" disabled><i class="fa fa-lock mr3"></i> Đặc quyền Điều hành viên</button>';
                }
            }

            // Nếu là item Banned
            if (exclusive === 'banned') {
                return '<button type="button" class="but but-sm c-red radius disabled znd-btn-exclusive-disabled" disabled><i class="fa fa-ban mr3"></i> Tài khoản vi phạm</button>';
            }

            if (status === 'equipped') {
                return '<button type="button" class="but but-sm c-green radius znd-btn znd-btn-unequip" data-znd-act="unequip" data-type="' + type + '" data-id="' + itemId + '"><i class="fa fa-check-circle mr3"></i> Đang dùng (Tháo)</button>';
            } else if (status === 'owned') {
                return '<button type="button" class="but but-sm c-blue radius znd-btn znd-btn-equip" data-znd-act="equip" data-type="' + type + '" data-id="' + itemId + '"><i class="fa fa-bolt mr3"></i> Trang bị</button>';
            } else {
                return '<button type="button" class="but but-sm c-yellow radius znd-btn znd-btn-buy" data-znd-act="buy" data-type="' + type + '" data-id="' + itemId + '" data-duration="' + duration + '"><i class="fa fa-shopping-cart mr3"></i> Mua ngay (' + priceText + ')</button>';
            }
        },

        bindEvents: function () {
            var self = this;

            // Tự động làm tươi gradient ID khi người dùng mở Menu trượt di động
            $(document).on('click', '[data-toggle-class="mobile-navbar-show"], .navbar-toggle', function () {
                self.fixMobileNavbarSvgIds();
                setTimeout(function () {
                    self.fixMobileNavbarSvgIds();
                }, 150);
            });

            // Tự động áp dụng lại trang trí khi chuyển tab hoặc route AJAX
            $(document).on('shown.bs.tab route_back ajax_loaded', function () {
                self.applyUserPageDecor();
            });

            // =====================================================================
            // 1. MỞ & ĐÓNG MODAL (Tuân thủ modal-backdrop-close.md)
            // =====================================================================
            $(document).on('click', '[data-znd-action="modal"], [data-action="zibll_nick_decor_modal"], .open-nick-decor-modal', function (e) {
                e.preventDefault();
                e.stopPropagation();

                // Chỉ cho phép mở modal khi đã đăng nhập
                if (!self.isUserLoggedIn()) {
                    self.showToast((typeof znd_vars !== 'undefined' && znd_vars.i18n && znd_vars.i18n.login_require) || 'Vui lòng đăng nhập để mở tủ đồ và mua sắm!', 'warning');
                    if ($('.signin-loader').length) {
                        $('.signin-loader').first().trigger('click');
                    }
                    return;
                }

                self.openModal();
            });

            // Đóng Modal Cửa hàng (#znd-shop-modal) khi click nút đóng của chính nó
            $(document).on('click', '#znd-shop-modal .znd-modal-close, #znd-shop-modal [data-dismiss="modal"]', function (e) {
                // Tuyệt đối không đóng shop modal nếu click nút hủy/đóng của popup xác nhận mua
                if ($(this).closest('#znd-confirm-modal').length) {
                    return;
                }
                e.preventDefault();
                self.closeModal();
            });

            // Bắt mousedown & mouseup trên backdrop để tránh đóng nhầm khi kéo chuột từ trong ra ngoài (modal-backdrop-close.md)
            var backdropMouseDownTarget = null;

            $(document).on('mousedown', '#znd-shop-modal', function (e) {
                backdropMouseDownTarget = e.target;
            });

            $(document).on('mouseup', '#znd-shop-modal', function (e) {
                if (backdropMouseDownTarget === this && e.target === this) {
                    self.closeModal();
                }
                backdropMouseDownTarget = null;
            });

            // Bắt mousedown & mouseup trên backdrop của Popup xác nhận mua đồ (#znd-confirm-modal)
            var confirmBackdropMouseDownTarget = null;
            $(document).on('mousedown', '#znd-confirm-modal', function (e) {
                confirmBackdropMouseDownTarget = e.target;
            });

            $(document).on('mouseup', '#znd-confirm-modal', function (e) {
                if (confirmBackdropMouseDownTarget === this && e.target === this) {
                    self.closeConfirmModal();
                }
                confirmBackdropMouseDownTarget = null;
            });

            $(document).on('click', '.znd-confirm-cancel, #znd-confirm-modal .close, #znd-confirm-modal [data-dismiss="modal"]', function (e) {
                e.preventDefault();
                e.stopPropagation();
                self.closeConfirmModal();
            });

            $(document).on('click', '.znd-confirm-ok', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var cb = self.confirmCallback;
                self.closeConfirmModal();
                if (typeof cb === 'function') {
                    cb();
                }
            });

            // Phím Escape để đóng modal
            $(document).on('keydown', function (e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    if ($('#znd-confirm-modal').is(':visible')) {
                        self.closeConfirmModal();
                        e.stopPropagation();
                        return;
                    }
                    if ($('#znd-shop-modal').is(':visible')) {
                        self.closeModal();
                    }
                }
            });

            // Đồng bộ trạng thái lớp body khi đóng/mở Shop Modal qua Bootstrap Modal
            $('#znd-shop-modal').on('hidden.bs.modal', function () {
                $('body').removeClass('znd-modal-open');
                $(this).css('display', 'none');
            }).on('shown.bs.modal', function () {
                $('body').addClass('znd-modal-open');
                $(this).css('display', 'flex');
            });

            // Khi popup xác nhận mua đóng: Đảm bảo giữ nguyên trạng thái mở và backdrop cho Shop Modal
            $(document).on('hidden.bs.modal', '#znd-confirm-modal', function () {
                $(this).css('display', 'none');
                var $shopModal = $('#znd-shop-modal');
                if ($shopModal.length && ($shopModal.hasClass('in') || $shopModal.is(':visible'))) {
                    $('body').addClass('modal-open znd-modal-open');
                    $shopModal.css('display', 'flex');
                    if (!$('.modal-backdrop.in, .modal-backdrop.show').length) {
                        $('<div class="modal-backdrop fade in"></div>').appendTo('body');
                    }
                }
            });

            // =====================================================================
            // 1.1. XỬ LÝ NÚT NẠP THÊM & MODAL NẠP TIỀN CỦA THEME ZIBLL (REFRESH_MODAL)
            // =====================================================================
            // Đảm bảo click nút Nạp thêm luôn kích hoạt refresh_modal của Theme Zibll
            $(document).on('click', '.znd-charge-btn a[data-toggle="RefreshModal"]', function (e) {
                var $btn = $(this);
                var remote = $btn.attr('data-remote');
                if (typeof window.refresh_modal === 'function' && remote) {
                    e.preventDefault();
                    window.refresh_modal({
                        class: $btn.attr('data-class') || 'modal-mini full-sm',
                        height: $btn.attr('data-height') || 330,
                        mobile_from_bottom: !!$btn.attr('mobile-bottom'),
                        new: $btn.attr('new'),
                        touch_close: !$btn.attr('no-touch'),
                        remote: remote
                    });
                    return false;
                }
            });

            // Khi modal nạp tiền của Zibll mở: Đảm bảo z-index luôn cao nhất và backdrop nằm trên Shop Modal
            $(document).on('show.bs.modal shown.bs.modal', '#refresh_modal, .modal[id^="refresh_modal"]', function () {
                var $modal = $(this);
                $modal.css({
                    'z-index': 100070,
                    'display': 'flex'
                });
                setTimeout(function () {
                    $('.modal-backdrop.in, .modal-backdrop.show').last().css('z-index', 100065);
                }, 10);
            });

            // Khi modal nạp tiền đóng lại: Khôi phục cuộn cho Shop Modal và tự động cập nhật số dư mới nhất
            $(document).on('hidden.bs.modal', '#refresh_modal, .modal[id^="refresh_modal"]', function () {
                if ($('#znd-shop-modal').hasClass('in') || $('#znd-shop-modal').is(':visible')) {
                    $('body').addClass('modal-open znd-modal-open');
                    self.refreshUserBalance();
                }
            });

            // =====================================================================
            // 2. CHUYỂN TAB ĐIỀU HƯỚNG (THEME ZIBLL TAB-NAV-THEME)
            // =====================================================================
            $(document).on('click', '.znd-tab-btn, .znd-nav-tabs li a, .znd-nav-tabs li', function (e) {
                e.preventDefault();
                var $li = $(this).closest('li');
                var tab = $li.data('tab') || $(this).data('tab');
                if (!tab) return;

                var $wrapper = $(this).closest('.znd-shop-wrapper');

                $wrapper.find('.znd-nav-tabs li').removeClass('active');
                $li.addClass('active');

                $wrapper.find('.znd-tab-content').removeClass('active');
                $wrapper.find('#znd-tab-' + tab).addClass('active');
            });

            // =====================================================================
            // 3. XEM THỬ DIỆN MẠO & MỞ THANH CHI TIẾT (ACTION DRAWER)
            // =====================================================================
            $(document).on('click', '.znd-card', function (e) {
                if ($(e.target).closest('.znd-btn-buy, .znd-btn-equip, .znd-btn-unequip').length) {
                    return;
                }

                var $card = $(this);
                var id    = $card.data('id');
                var type  = $card.data('type');
                var name  = $card.data('name');
                var desc  = $card.data('desc') || '';
                var priceText = $card.data('price-text') || '';
                var duration  = $card.data('duration') || '';
                var status    = $card.data('status') || 'available';
                var anim  = $card.data('anim');
                var exclusive = $card.data('exclusive') || '';

                var $wrapper = $card.closest('.znd-shop-wrapper');

                // 1. Đánh dấu card đang được xem thử & chọn
                $wrapper.find('.znd-card[data-type="' + type + '"]').removeClass('is-previewing is-selected');
                $card.addClass('is-previewing is-selected');

                // 2. Cập nhật Live Preview ở trên đầu
                if (type === 'icon') {
                    var rawSvg = $card.data('item-svg') || $card.find('.znd-card-icon-preview .znd-nick-icon').html();
                    var svg = self.prepareSvg(rawSvg);
                    var animClass = anim ? ' znd-anim-' + anim : '';
                    $wrapper.find('#znd-preview-icon').html('<span class="' + animClass + '">' + svg + '</span>');
                    $wrapper.find('#znd-preview-desc').text('Đang thử icon: ' + name);
                } else if (type === 'color') {
                    var cssClass = $card.data('css');
                    var animClass = anim ? ' znd-anim-' + anim : '';

                    var $previewName = $wrapper.find('#znd-preview-name');
                    $previewName[0].className = $previewName[0].className.replace(/\bznd-color-\S+/g, '');
                    $previewName[0].className = $previewName[0].className.replace(/\bznd-anim-\S+/g, '');

                    $previewName.addClass(cssClass + animClass);
                    $wrapper.find('#znd-preview-desc').text('Đang thử màu: ' + name);
                }

                // 3. Cập nhật thông tin vào thanh Action Drawer ở đáy
                var $drawer = $wrapper.find('#znd-action-drawer');
                $drawer.find('#znd-drawer-name').text(name);
                $drawer.find('#znd-drawer-desc').text(desc);

                // Đọc các gói giá theo thời hạn (7 ngày, 30 ngày, Vĩnh viễn)
                var prices = $card.data('prices');
                if (typeof prices === 'string') {
                    try { prices = JSON.parse(prices); } catch (e) { prices = null; }
                }

                var $drawerMiddle = $drawer.find('#znd-drawer-middle');
                var $packagesContainer = $drawer.find('#znd-drawer-packages');
                var selectedDuration = 7;

                if (status === 'available' && prices && !exclusive) {
                    var pillsHtml = '';
                    var order = [7, 30, 0];
                    order.forEach(function (days) {
                        var opt = prices[days] || prices[String(days)];
                        if (opt) {
                            var isActive = (days === 7) ? ' active' : '';
                            var badgeHtml = opt.badge ? '<span class="pill-badge">' + opt.badge + '</span>' : '';
                            pillsHtml += '<div class="znd-duration-pill' + isActive + '" data-days="' + opt.days + '" data-price="' + opt.price + '" data-price-text="' + opt.price_text + '">';
                            pillsHtml += '<span class="pill-time">' + opt.name + '</span>';
                            pillsHtml += '<span class="pill-price">' + opt.price_text + '</span>';
                            pillsHtml += badgeHtml;
                            pillsHtml += '</div>';

                            if (days === 7) {
                                priceText = opt.price_text;
                                duration = opt.name;
                            }
                        }
                    });

                    $packagesContainer.html(pillsHtml);
                    $drawerMiddle.show();
                } else {
                    $drawerMiddle.hide();
                    $packagesContainer.empty();
                }

                $drawer.find('#znd-drawer-price').text(priceText);
                $drawer.find('#znd-drawer-duration').text(duration);

                // Preview icon / màu trong drawer
                if (type === 'icon') {
                    var rawSvg = $card.data('item-svg') || $card.find('.znd-card-icon-preview .znd-nick-icon').html();
                    var drawerSvg = self.prepareSvg(rawSvg);
                    var animClass = anim ? ' znd-anim-' + anim : '';
                    $drawer.find('#znd-drawer-preview').html('<span class="znd-nick-icon ' + animClass + '">' + drawerSvg + '</span>');
                } else {
                    var cssClass = $card.data('css');
                    var animClass = anim ? ' znd-anim-' + anim : '';
                    $drawer.find('#znd-drawer-preview').html('<span class="znd-sample-text ' + cssClass + animClass + '">Aa</span>');
                }

                // Nút thao tác tương ứng
                var btnHtml = self.getDrawerBtnHtml(type, id, status, priceText, selectedDuration, exclusive);
                $drawer.find('#znd-drawer-btn-wrap').html(btnHtml);

                if ($drawer.is(':hidden')) {
                    $drawer.slideDown(200);
                }
            });

            // Chọn gói thời hạn trong Action Drawer (7 ngày, 30 ngày, Vĩnh viễn)
            $(document).on('click', '.znd-duration-pill', function (e) {
                e.preventDefault();
                var $pill = $(this);
                if ($pill.hasClass('active')) return;

                $pill.siblings().removeClass('active');
                $pill.addClass('active');

                var days      = $pill.data('days');
                var priceText = $pill.data('price-text');
                var daysText  = $pill.find('.pill-time').text();

                var $drawer = $pill.closest('#znd-action-drawer');
                $drawer.find('#znd-drawer-price').text(priceText);
                $drawer.find('#znd-drawer-duration').text(daysText);

                var $buyBtn = $drawer.find('.znd-btn-buy');
                $buyBtn.data('duration', days).attr('data-duration', days);
                $buyBtn.html('<i class="fa fa-shopping-cart mr3"></i> Mua ngay (' + priceText + ')');
            });

            // Đóng drawer chi tiết khi bấm nút x
            $(document).on('click', '#znd-drawer-close', function (e) {
                e.preventDefault();
                $(this).closest('#znd-action-drawer').slideUp(200);
            });

            // =====================================================================
            // 4. MUA VẬT PHẨM (BUY) - TÍCH HỢP POPUP CHUẨN ZIBLL VÀ HTMX SWAP
            // =====================================================================
            $(document).on('click', '.znd-btn-buy', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var $btn = $(this);
                if ($btn.prop('disabled')) return;

                if (!znd_vars.is_logged_in) {
                    self.showToast((znd_vars.i18n && znd_vars.i18n.login_require) || 'Vui lòng đăng nhập để mở tủ đồ và mua sắm!', 'error');
                    if ($('.signin-loader').length) {
                        $('.signin-loader').first().click();
                    }
                    return;
                }

                var type     = $btn.data('type');
                var itemId   = $btn.data('id');
                var duration = typeof $btn.data('duration') !== 'undefined' ? $btn.data('duration') : 7;
                var $wrapper = $btn.closest('.znd-shop-wrapper');
                var $card    = $wrapper.find('.znd-card[data-type="' + type + '"][data-id="' + itemId + '"]');
                var name     = $card.length ? $card.data('name') : '';
                var priceText = $wrapper.find('#znd-drawer-price').text() || $card.data('price-text') || '';

                var durationName = (duration == 0) ? 'Vĩnh viễn' : (duration + ' ngày');

                // Tạo preview mini cho popup xác nhận
                var previewHtml = '';
                if (type === 'icon') {
                    var rawSvg = $card.data('item-svg') || $card.find('.znd-card-icon-preview .znd-nick-icon').html();
                    if (rawSvg) {
                        previewHtml = '<span class="znd-nick-icon" style="width: 48px; height: 48px; font-size: 44px;">' + self.prepareSvg(rawSvg) + '</span>';
                    }
                } else if (type === 'color') {
                    var cssClass = $card.data('css') || '';
                    var anim = $card.data('anim') || '';
                    var animClass = anim ? ' znd-anim-' + anim : '';
                    previewHtml = '<span class="znd-sample-text ' + cssClass + animClass + '" style="font-size: 26px; padding: 6px 18px; border-radius: 8px; background: rgba(0,0,0,0.04);">Aa</span>';
                }

                // Mở popup xác nhận giao diện Zibll thay thế window.confirm
                self.openConfirmModal({
                    name: name,
                    previewHtml: previewHtml,
                    durationName: durationName,
                    priceText: priceText,
                    onConfirm: function () {
                        var buyingText = (znd_vars.i18n && znd_vars.i18n.buying) ? znd_vars.i18n.buying : 'Đang xử lý mua...';
                        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr3"></i> ' + buyingText);

                        $.ajax({
                            url: znd_vars.ajax_url,
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                action: 'znd_buy_item',
                                nonce: znd_vars.nonce,
                                type: type,
                                item_id: itemId,
                                duration: duration
                            },
                            success: function (res) {
                                $btn.prop('disabled', false);
                                if (res && res.success) {
                                    self.showToast((res.data && res.data.message) || 'Mua thành công! Vật phẩm đã được thêm vào tủ đồ.', 'success');

                                    // CẬP NHẬT GIAO DIỆN THỜI GIAN THỰC TOÀN TRANG (HTMX REACTIVE DOM SWAP)
                                    self.updateUserDecorOnPage(res.data);

                                    // Ẩn thanh chọn gói vì đã sở hữu
                                    $wrapper.find('#znd-drawer-middle').hide();

                                    // Đổi trạng thái các card cùng loại khác
                                    $wrapper.find('.znd-card[data-type="' + type + '"]').each(function () {
                                        if ($(this).data('status') === 'equipped') {
                                            $(this).data('status', 'owned').removeClass('is-equipped');
                                            $(this).find('.znd-badge-status').removeClass('status-equipped').addClass('status-owned').html('<i class="fa fa-circle"></i>');
                                        }
                                    });

                                    if ($card.length) {
                                        var newDurationText = (duration == 0) ? 'Vĩnh viễn' : 'Hết hạn: ' + duration + ' ngày';
                                        $card.data('status', 'equipped').data('duration', newDurationText).addClass('is-equipped is-owned');
                                        $wrapper.find('#znd-drawer-price').text('Đang dùng');
                                        $wrapper.find('#znd-drawer-duration').text(newDurationText);

                                        if (!$card.find('.znd-badge-status').length) {
                                            $card.append('<span class="znd-badge-status status-equipped" title="Đang dùng"><i class="fa fa-check"></i></span>');
                                        } else {
                                            $card.find('.znd-badge-status').removeClass('status-owned').addClass('status-equipped').html('<i class="fa fa-check"></i>');
                                        }
                                    }

                                    // Cập nhật lại nút trên thanh Drawer
                                    $wrapper.find('#znd-drawer-btn-wrap').html(self.getDrawerBtnHtml(type, itemId, 'equipped', 'Đang dùng'));

                                    // Tự động chuyển nhẹ sang tab Tủ đồ sau 350ms để thành viên nhìn thấy ngay vật phẩm mới mua
                                    setTimeout(function () {
                                        var $wardrobeTabBtn = $wrapper.find('.znd-nav-tabs li[data-tab="wardrobe"] a, .znd-nav-tabs li[data-tab="wardrobe"]');
                                        if ($wardrobeTabBtn.length) {
                                            $wardrobeTabBtn.first().trigger('click');
                                        }
                                    }, 350);
                                } else {
                                    var errMsg = (res && res.data && res.data.message) ? res.data.message : 'Giao dịch không thành công!';
                                    self.showToast(errMsg, 'error');
                                    $btn.html('<i class="fa fa-shopping-cart mr3"></i> Mua ngay');
                                }
                            },
                            error: function (xhr) {
                                $btn.prop('disabled', false).html('<i class="fa fa-shopping-cart mr3"></i> Mua ngay');
                                var msg = 'Lỗi kết nối máy chủ, vui lòng thử lại!';
                                if (xhr && xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message) {
                                    msg = xhr.responseJSON.data.message;
                                }
                                self.showToast(msg, 'error');
                            }
                        });
                    }
                });
            });

            // =====================================================================
            // 5. TRANG BỊ VẬT PHẨM (EQUIP) - TÍCH HỢP HTMX REACTIVE DOM SWAP
            // =====================================================================
            $(document).on('click', '.znd-btn-equip', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var $btn = $(this);
                if ($btn.prop('disabled')) return;

                var type   = $btn.data('type');
                var itemId = $btn.data('id');
                var $wrapper = $btn.closest('.znd-shop-wrapper');
                var $card  = $wrapper.find('.znd-card[data-type="' + type + '"][data-id="' + itemId + '"]');

                var equipText = (znd_vars.i18n && znd_vars.i18n.equipping) ? znd_vars.i18n.equipping : 'Đang trang bị...';
                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr3"></i> ' + equipText);

                $.ajax({
                    url: znd_vars.ajax_url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'znd_equip_item',
                        nonce: znd_vars.nonce,
                        type: type,
                        item_id: itemId
                    },
                    success: function (res) {
                        $btn.prop('disabled', false);
                        if (res && res.success) {
                            self.showToast((res.data && res.data.message) || 'Trang bị thành công!', 'success');

                            // CẬP NHẬT GIAO DIỆN THỜI GIAN THỰC TOÀN TRANG (HTMX REACTIVE DOM SWAP)
                            self.updateUserDecorOnPage(res.data);

                            // Bỏ trang bị các item cùng loại khác
                            $wrapper.find('.znd-card[data-type="' + type + '"]').each(function () {
                                if ($(this).data('status') === 'equipped') {
                                    $(this).data('status', 'owned').removeClass('is-equipped');
                                    $(this).find('.znd-badge-status').removeClass('status-equipped').addClass('status-owned').html('<i class="fa fa-circle"></i>');
                                }
                            });

                            if ($card.length) {
                                $card.data('status', 'equipped').addClass('is-equipped');
                                if (!$card.find('.znd-badge-status').length) {
                                    $card.append('<span class="znd-badge-status status-equipped" title="Đang dùng"><i class="fa fa-check"></i></span>');
                                } else {
                                    $card.find('.znd-badge-status').removeClass('status-owned').addClass('status-equipped').html('<i class="fa fa-check"></i>');
                                }
                            }

                            // Cập nhật lại nút trên thanh Drawer
                            $wrapper.find('#znd-drawer-btn-wrap').html(self.getDrawerBtnHtml(type, itemId, 'equipped', $card.data('price-text'), 7, $card.data('exclusive')));
                        } else {
                            var errMsg = (res && res.data && res.data.message) ? res.data.message : 'Có lỗi xảy ra!';
                            self.showToast(errMsg, 'error');
                            $btn.html('<i class="fa fa-bolt mr3"></i> Trang bị');
                        }
                    },
                    error: function () {
                        $btn.prop('disabled', false).html('<i class="fa fa-bolt mr3"></i> Trang bị');
                        self.showToast('Lỗi kết nối máy chủ!', 'error');
                    }
                });
            });

            // =====================================================================
            // 6. THÁO VẬT PHẨM (UNEQUIP) - TÍCH HỢP HTMX REACTIVE DOM SWAP
            // =====================================================================
            $(document).on('click', '.znd-btn-unequip', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var $btn = $(this);
                if ($btn.prop('disabled')) return;

                var type   = $btn.data('type');
                var itemId = $btn.data('id');
                var $wrapper = $btn.closest('.znd-shop-wrapper');
                var $card  = $wrapper.find('.znd-card[data-type="' + type + '"][data-id="' + itemId + '"]');

                var unequipText = (znd_vars.i18n && znd_vars.i18n.unequipping) ? znd_vars.i18n.unequipping : 'Đang tháo...';
                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr3"></i> ' + unequipText);

                $.ajax({
                    url: znd_vars.ajax_url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'znd_unequip_item',
                        nonce: znd_vars.nonce,
                        type: type
                    },
                    success: function (res) {
                        $btn.prop('disabled', false);
                        if (res && res.success) {
                            self.showToast((res.data && res.data.message) || 'Đã tháo trang bị!', 'success');

                            // CẬP NHẬT GIAO DIỆN THỜI GIAN THỰC TOÀN TRANG (HTMX REACTIVE DOM SWAP)
                            self.updateUserDecorOnPage(res.data);

                            if ($card.length) {
                                $card.data('status', 'owned').removeClass('is-equipped');
                                $card.find('.znd-badge-status').removeClass('status-equipped').addClass('status-owned').html('<i class="fa fa-circle"></i>');
                            }

                            // Cập nhật lại nút trên thanh Drawer
                            $wrapper.find('#znd-drawer-btn-wrap').html(self.getDrawerBtnHtml(type, itemId, 'owned', $card.data('price-text'), 7, $card.data('exclusive')));
                        } else {
                            var errMsg = (res && res.data && res.data.message) ? res.data.message : 'Có lỗi xảy ra!';
                            self.showToast(errMsg, 'error');
                            $btn.html('<i class="fa fa-check-circle mr3"></i> Đang dùng (Tháo)');
                        }
                    },
                    error: function () {
                        $btn.prop('disabled', false).html('<i class="fa fa-check-circle mr3"></i> Đang dùng (Tháo)');
                        self.showToast('Lỗi kết nối máy chủ!', 'error');
                    }
                });
            });
        }
    };

    $(document).ready(function () {
        ZND.init();
    });

})(jQuery);
