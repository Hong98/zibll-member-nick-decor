/**
 * Zibll Member Nickname & Badge Pro - Frontend Controller
 * Tuân thủ quy tắc modal-backdrop-close (mousedown & mouseup trên backdrop)
 */

(function ($) {
    'use strict';

    var ZND = {
        init: function () {
            this.bindEvents();
            this.injectUserMenuButton();
        },

        // Mở Modal
        openModal: function () {
            var $modal = $('#znd-shop-modal');
            if (!$modal.length) return;

            $modal.show();
            // Trigger reflow
            $modal[0].offsetHeight;
            $modal.addClass('is-open');
            $('body').addClass('znd-modal-open');
        },

        // Đóng Modal
        closeModal: function () {
            var $modal = $('#znd-shop-modal');
            if (!$modal.length) return;

            $modal.removeClass('is-open');
            setTimeout(function () {
                $modal.hide();
                $('body').removeClass('znd-modal-open');
            }, 300);
        },

        // Thông báo Toast đơn giản, đẹp mắt
        showToast: function (message, type) {
            type = type || 'success';
            var bg = (type === 'success') ? '#10b981' : '#ef4444';
            var icon = (type === 'success') ? 'fa-check-circle' : 'fa-exclamation-circle';

            var $toast = $('<div class="znd-toast-msg" style="position:fixed;bottom:24px;right:24px;z-index:9999999;background:' + bg + ';color:#fff;padding:12px 20px;border-radius:10px;font-size:14px;box-shadow:0 10px 25px rgba(0,0,0,0.2);display:flex;align-items:center;gap:10px;animation:znd-fade-in 0.3s ease;"><i class="fa ' + icon + '"></i> ' + message + '</div>');
            $('body').append($toast);

            setTimeout(function () {
                $toast.fadeOut(300, function () {
                    $(this).remove();
                });
            }, 3000);
        },

        // Tự động chèn icon vào hàng cuối cùng của User Dropdown Box Theme Zibll
        injectUserMenuButton: function () {
            try {
                var insertBtn = function () {
                    // Dọn dẹp các khối nút ở đáy cũ nếu có
                    $('.znd-user-menu-bottom').remove();

                    $('.sub-user-box').each(function () {
                        var $box = $(this);
                        var $allHrefs = $box.find('.header-user-href');
                        if (!$allHrefs.length) return;

                        var $lastHref = $allHrefs.last();

                        // Nếu nút đã ở đúng hàng cuối cùng thì giữ nguyên
                        if ($lastHref.find('.znd-user-href-btn').length) {
                            return;
                        }

                        // Xóa nút ở các hàng khác nếu có
                        $box.find('.znd-user-href-btn').remove();
                        $allHrefs.removeClass('has-znd-btn');

                        // Cấu trúc icon chuẩn Theme Zibll với màu c-theme đồng bộ với màu chủ đề của website
                        var btnHtml = '<a href="javascript:;" data-action="zibll_nick_decor_modal" class="open-nick-decor-modal znd-user-href-btn" title="Cửa hàng màu & Icon nick">' +
                                      '<div class="badg mb6 toggle-radius c-theme"><i class="fa fa-paint-brush"></i></div>' +
                                      '<div class="c-theme translate-ignore">Màu & Icon</div>' +
                                      '</a>';

                        $lastHref.addClass('has-znd-btn').append(btnHtml);
                    });
                };

                insertBtn();
                // Lắng nghe khi hover hoặc click menu tài khoản để chèn nút an toàn
                $(document).on('mouseenter click', '.navbar-avatar, .sub-user-box', function () {
                    insertBtn();
                });
            } catch (e) {
                console.warn('ZND injectUserMenuButton error:', e);
            }
        },

        bindEvents: function () {
            var self = this;

            // =====================================================================
            // 1. MỞ & ĐÓNG MODAL (Tuân thủ modal-backdrop-close.md)
            // =====================================================================
            $(document).on('click', '[data-action="zibll_nick_decor_modal"], .open-nick-decor-modal', function (e) {
                e.preventDefault();
                self.openModal();
            });

            $(document).on('click', '.znd-modal-close', function (e) {
                e.preventDefault();
                self.closeModal();
            });

            // Bắt mousedown & mouseup trên backdrop để tránh đóng nhầm khi kéo chuột từ trong ra ngoài
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
                    if ($('#znd-shop-modal').hasClass('is-open')) {
                        self.closeModal();
                    }
                }
            });

            // =====================================================================
            // 2. CHUYỂN TAB ĐIỀU HƯỚNG
            // =====================================================================
            $(document).on('click', '.znd-tab-btn', function (e) {
                e.preventDefault();
                var tab = $(this).data('tab');
                var $wrapper = $(this).closest('.znd-shop-wrapper');

                $wrapper.find('.znd-tab-btn').removeClass('active');
                $(this).addClass('active');

                $wrapper.find('.znd-tab-content').removeClass('active');
                $wrapper.find('#znd-tab-' + tab).addClass('active');
            });

            // =====================================================================
            // 3. XEM THỬ DIỆN MẠO (LIVE PREVIEW)
            // =====================================================================
            $(document).on('click', '.znd-btn-preview, .znd-card', function (e) {
                // Không trigger nếu click vào nút mua/trang bị/tháo
                if ($(e.target).closest('.znd-btn-buy, .znd-btn-equip, .znd-btn-unequip').length) {
                    return;
                }

                var $card = $(this).closest('.znd-card');
                var type  = $card.data('type');
                var name  = $card.data('name');
                var anim  = $card.data('anim');

                var $wrapper = $card.closest('.znd-shop-wrapper');

                if (type === 'icon') {
                    var svg = $card.data('item-svg') || $card.find('.znd-card-icon-preview .znd-nick-icon').html();
                    var animClass = anim ? ' znd-anim-' + anim : '';
                    $wrapper.find('#znd-preview-icon').html('<span class="' + animClass + '">' + svg + '</span>');
                    $wrapper.find('#znd-preview-desc').text('Đang thử icon: ' + name);
                } else if (type === 'color') {
                    var cssClass = $card.data('css');
                    var animClass = anim ? ' znd-anim-' + anim : '';

                    var $previewName = $wrapper.find('#znd-preview-name');
                    // Gỡ bỏ các class znd-color-* cũ
                    $previewName[0].className = $previewName[0].className.replace(/\bznd-color-\S+/g, '');
                    $previewName[0].className = $previewName[0].className.replace(/\bznd-anim-\S+/g, '');

                    $previewName.addClass(cssClass + animClass);
                    $wrapper.find('#znd-preview-desc').text('Đang thử màu: ' + name);
                }
            });

            // =====================================================================
            // 4. MUA VẬT PHẨM (BUY)
            // =====================================================================
            $(document).on('click', '.znd-btn-buy', function (e) {
                e.preventDefault();
                var $btn = $(this);
                if ($btn.prop('disabled')) return;

                if (!znd_vars.is_logged_in) {
                    self.showToast(znd_vars.i18n.login_require, 'error');
                    if ($('.signin-loader').length) {
                        $('.signin-loader').first().click();
                    }
                    return;
                }

                var type   = $btn.data('type');
                var itemId = $btn.data('id');
                var $card  = $btn.closest('.znd-card');
                var name   = $card.data('name');

                if (!confirm(znd_vars.i18n.confirm_buy + '\n' + name)) {
                    return;
                }

                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + znd_vars.i18n.buying);

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
                        if (res.success) {
                            self.showToast(res.data.message, 'success');

                            // Cập nhật số dư hiển thị
                            if (typeof res.data.new_balance !== 'undefined') {
                                $('#znd-current-balance').text(Number(res.data.new_balance).toLocaleString());
                            }

                            // Đổi trạng thái card sang equipped
                            var $wrapper = $card.closest('.znd-shop-wrapper');
                            $wrapper.find('.znd-card[data-type="' + type + '"]').removeClass('is-equipped');
                            $card.addClass('is-equipped is-owned');

                            // Cập nhật nút bấm
                            $btn.replaceWith('<button type="button" class="znd-btn znd-btn-unequip" data-action="unequip" data-type="' + type + '"><i class="fa fa-check-circle"></i> Đang dùng (Tháo)</button>');

                            // Kích hoạt xem thử
                            $card.find('.znd-btn-preview').click();
                        } else {
                            self.showToast(res.data ? res.data.message : 'Có lỗi xảy ra!', 'error');
                            $btn.html('<i class="fa fa-shopping-cart"></i> Mua ngay');
                        }
                    },
                    error: function () {
                        $btn.prop('disabled', false).html('<i class="fa fa-shopping-cart"></i> Mua ngay');
                        self.showToast('Lỗi kết nối máy chủ, vui lòng thử lại!', 'error');
                    }
                });
            });

            // =====================================================================
            // 5. TRANG BỊ VẬT PHẨM (EQUIP)
            // =====================================================================
            $(document).on('click', '.znd-btn-equip', function (e) {
                e.preventDefault();
                var $btn = $(this);
                if ($btn.prop('disabled')) return;

                var type   = $btn.data('type');
                var itemId = $btn.data('id');
                var $card  = $btn.closest('.znd-card');

                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + znd_vars.i18n.equipping);

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
                        if (res.success) {
                            self.showToast(res.data.message, 'success');

                            var $wrapper = $card.closest('.znd-shop-wrapper');
                            // Bỏ trang bị các item cùng loại khác
                            $wrapper.find('.znd-card[data-type="' + type + '"]').each(function () {
                                $(this).removeClass('is-equipped');
                                var otherId = $(this).data('id');
                                if ($(this).hasClass('is-owned')) {
                                    $(this).find('.znd-btn-unequip').replaceWith('<button type="button" class="znd-btn znd-btn-equip" data-action="equip" data-type="' + type + '" data-id="' + otherId + '"><i class="fa fa-bolt"></i> Trang bị</button>');
                                }
                            });

                            $card.addClass('is-equipped');
                            $btn.replaceWith('<button type="button" class="znd-btn znd-btn-unequip" data-action="unequip" data-type="' + type + '"><i class="fa fa-check-circle"></i> Đang dùng (Tháo)</button>');

                            // Kích hoạt xem thử
                            $card.find('.znd-btn-preview').click();
                        } else {
                            self.showToast(res.data ? res.data.message : 'Có lỗi xảy ra!', 'error');
                            $btn.html('<i class="fa fa-bolt"></i> Trang bị');
                        }
                    },
                    error: function () {
                        $btn.prop('disabled', false).html('<i class="fa fa-bolt"></i> Trang bị');
                        self.showToast('Lỗi kết nối máy chủ!', 'error');
                    }
                });
            });

            // =====================================================================
            // 6. THÁO VẬT PHẨM (UNEQUIP)
            // =====================================================================
            $(document).on('click', '.znd-btn-unequip', function (e) {
                e.preventDefault();
                var $btn = $(this);
                if ($btn.prop('disabled')) return;

                var type  = $btn.data('type');
                var $card = $btn.closest('.znd-card');
                var itemId = $card.data('id');

                $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> ' + znd_vars.i18n.unequipping);

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
                        if (res.success) {
                            self.showToast(res.data.message, 'success');
                            $card.removeClass('is-equipped');
                            $btn.replaceWith('<button type="button" class="znd-btn znd-btn-equip" data-action="equip" data-type="' + type + '" data-id="' + itemId + '"><i class="fa fa-bolt"></i> Trang bị</button>');

                            // Xóa khỏi khung xem trước
                            var $wrapper = $card.closest('.znd-shop-wrapper');
                            if (type === 'icon') {
                                $wrapper.find('#znd-preview-icon').empty();
                            } else if (type === 'color') {
                                var $pName = $wrapper.find('#znd-preview-name');
                                $pName[0].className = $pName[0].className.replace(/\bznd-color-\S+/g, '');
                                $pName[0].className = $pName[0].className.replace(/\bznd-anim-\S+/g, '');
                            }
                            $wrapper.find('#znd-preview-desc').text('Đã tháo trang bị!');
                        } else {
                            self.showToast(res.data ? res.data.message : 'Có lỗi xảy ra!', 'error');
                            $btn.html('<i class="fa fa-check-circle"></i> Đang dùng (Tháo)');
                        }
                    },
                    error: function () {
                        $btn.prop('disabled', false).html('<i class="fa fa-check-circle"></i> Đang dùng (Tháo)');
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
