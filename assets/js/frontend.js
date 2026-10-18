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

        // Mở Modal (Tích hợp cơ chế Bootstrap Modal của Theme Zibll)
        openModal: function () {
            var $modal = $('#znd-shop-modal');
            if (!$modal.length) return;

            if ($.fn.modal) {
                $modal.modal({ backdrop: 'static', keyboard: true }).modal('show');
            } else {
                $modal.addClass('in is-open').show();
                $('body').addClass('modal-open');
            }
            $modal.css('display', 'flex');
        },

        // Đóng Modal (Tích hợp cơ chế Bootstrap Modal của Theme Zibll)
        closeModal: function () {
            var $modal = $('#znd-shop-modal');
            if (!$modal.length) return;

            if ($.fn.modal) {
                $modal.modal('hide');
            } else {
                $modal.removeClass('in is-open').hide();
                $('body').removeClass('modal-open');
            }
            $modal.css('display', 'none');
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
            var $toast = $('<div class="znd-toast-msg" style="position:fixed;top:30px;left:50%;transform:translateX(-50%);z-index:99999999;background:' + bg + ';color:#fff;padding:12px 24px;border-radius:50px;font-size:14px;font-weight:600;box-shadow:0 8px 30px rgba(0,0,0,0.35);display:flex;align-items:center;gap:10px;pointer-events:none;animation:znd-fade-in 0.3s ease;"><i class="fa ' + icon + '"></i> ' + message + '</div>');
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
        getDrawerBtnHtml: function (type, itemId, status, priceText) {
            if (status === 'equipped') {
                return '<button type="button" class="but but-sm c-green radius znd-btn znd-btn-unequip" data-znd-act="unequip" data-type="' + type + '" data-id="' + itemId + '"><i class="fa fa-check-circle mr3"></i> Đang dùng (Tháo)</button>';
            } else if (status === 'owned') {
                return '<button type="button" class="but but-sm c-blue radius znd-btn znd-btn-equip" data-znd-act="equip" data-type="' + type + '" data-id="' + itemId + '"><i class="fa fa-bolt mr3"></i> Trang bị</button>';
            } else {
                return '<button type="button" class="but but-sm c-yellow radius znd-btn znd-btn-buy" data-znd-act="buy" data-type="' + type + '" data-id="' + itemId + '"><i class="fa fa-shopping-cart mr3"></i> Mua ngay (' + priceText + ')</button>';
            }
        },

        bindEvents: function () {
            var self = this;

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

            $(document).on('click', '.znd-modal-close, [data-dismiss="modal"]', function (e) {
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

            // Phím Escape để đóng
            $(document).on('keydown', function (e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    if ($('#znd-shop-modal').is(':visible')) {
                        self.closeModal();
                    }
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

                var $wrapper = $card.closest('.znd-shop-wrapper');

                // 1. Đánh dấu card đang được xem thử & chọn
                $wrapper.find('.znd-card[data-type="' + type + '"]').removeClass('is-previewing is-selected');
                $card.addClass('is-previewing is-selected');

                // 2. Cập nhật Live Preview ở trên đầu
                if (type === 'icon') {
                    var svg = $card.data('item-svg') || $card.find('.znd-card-icon-preview .znd-nick-icon').html();
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
                $drawer.find('#znd-drawer-price').text(priceText);
                $drawer.find('#znd-drawer-duration').text(duration);

                // Preview icon / màu trong drawer
                if (type === 'icon') {
                    var svg = $card.data('item-svg') || $card.find('.znd-card-icon-preview .znd-nick-icon').html();
                    var animClass = anim ? ' znd-anim-' + anim : '';
                    $drawer.find('#znd-drawer-preview').html('<span class="znd-nick-icon ' + animClass + '">' + svg + '</span>');
                } else {
                    var cssClass = $card.data('css');
                    var animClass = anim ? ' znd-anim-' + anim : '';
                    $drawer.find('#znd-drawer-preview').html('<span class="znd-sample-text ' + cssClass + animClass + '">Aa</span>');
                }

                // Nút thao tác tương ứng
                var btnHtml = self.getDrawerBtnHtml(type, id, status, priceText);
                $drawer.find('#znd-drawer-btn-wrap').html(btnHtml);

                if ($drawer.is(':hidden')) {
                    $drawer.slideDown(200);
                }
            });

            // Đóng drawer chi tiết khi bấm nút x
            $(document).on('click', '#znd-drawer-close', function (e) {
                e.preventDefault();
                $(this).closest('#znd-action-drawer').slideUp(200);
            });

            // =====================================================================
            // 4. MUA VẬT PHẨM (BUY)
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

                var type   = $btn.data('type');
                var itemId = $btn.data('id');
                var $wrapper = $btn.closest('.znd-shop-wrapper');
                var $card  = $wrapper.find('.znd-card[data-type="' + type + '"][data-id="' + itemId + '"]');
                var name   = $card.length ? $card.data('name') : '';

                var confirmText = (znd_vars.i18n && znd_vars.i18n.confirm_buy) ? znd_vars.i18n.confirm_buy : 'Bạn có chắc chắn muốn mua vật phẩm này không?';
                if (!window.confirm(confirmText + '\n' + name)) {
                    return;
                }

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
                        item_id: itemId
                    },
                    success: function (res) {
                        $btn.prop('disabled', false);
                        if (res && res.success) {
                            self.showToast((res.data && res.data.message) || 'Mua thành công!', 'success');

                            // Cập nhật số dư hiển thị
                            if (res.data && typeof res.data.new_balance !== 'undefined') {
                                $('#znd-current-balance').text(Number(res.data.new_balance).toLocaleString());
                            }

                            // Đổi trạng thái các card cùng loại khác
                            $wrapper.find('.znd-card[data-type="' + type + '"]').each(function () {
                                if ($(this).data('status') === 'equipped') {
                                    $(this).data('status', 'owned').removeClass('is-equipped');
                                    $(this).find('.znd-badge-status').removeClass('status-equipped').addClass('status-owned').html('<i class="fa fa-circle"></i>');
                                }
                            });

                            if ($card.length) {
                                $card.data('status', 'equipped').addClass('is-equipped is-owned');
                                if (!$card.find('.znd-badge-status').length) {
                                    $card.append('<span class="znd-badge-status status-equipped" title="Đang dùng"><i class="fa fa-check"></i></span>');
                                } else {
                                    $card.find('.znd-badge-status').removeClass('status-owned').addClass('status-equipped').html('<i class="fa fa-check"></i>');
                                }
                            }

                            // Cập nhật lại nút trên thanh Drawer
                            $wrapper.find('#znd-drawer-btn-wrap').html(self.getDrawerBtnHtml(type, itemId, 'equipped', $card.data('price-text')));
                        } else {
                            var errMsg = (res && res.data && res.data.message) ? res.data.message : 'Giao dịch không thành công!';
                            self.showToast(errMsg, 'error');
                            $btn.html('<i class="fa fa-shopping-cart mr3"></i> Mua ngay (' + ($card.data('price-text') || '') + ')');
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
            });

            // =====================================================================
            // 5. TRANG BỊ VẬT PHẨM (EQUIP)
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
                            $wrapper.find('#znd-drawer-btn-wrap').html(self.getDrawerBtnHtml(type, itemId, 'equipped', $card.data('price-text')));
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
            // 6. THÁO VẬT PHẨM (UNEQUIP)
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

                            if ($card.length) {
                                $card.data('status', 'owned').removeClass('is-equipped');
                                $card.find('.znd-badge-status').removeClass('status-equipped').addClass('status-owned').html('<i class="fa fa-circle"></i>');
                            }

                            // Xóa khỏi khung xem trước
                            if (type === 'icon') {
                                $wrapper.find('#znd-preview-icon').empty();
                            } else if (type === 'color') {
                                var $pName = $wrapper.find('#znd-preview-name');
                                $pName[0].className = $pName[0].className.replace(/\bznd-color-\S+/g, '');
                                $pName[0].className = $pName[0].className.replace(/\bznd-anim-\S+/g, '');
                                $wrapper.find('#znd-preview-desc').text('Mặc định');
                            }

                            // Cập nhật lại nút trên thanh Drawer
                            $wrapper.find('#znd-drawer-btn-wrap').html(self.getDrawerBtnHtml(type, itemId, 'owned', $card.data('price-text')));
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
