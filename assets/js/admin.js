/**
 * Zibll Member Nickname & Badge Pro - Admin Controller
 */

(function ($) {
    'use strict';

    $(document).ready(function () {
        // Tìm kiếm thành viên
        $(document).on('click', '#znd_search_user_btn', function (e) {
            e.preventDefault();
            var keyword = $('#znd_search_user_input').val().trim();
            if (!keyword) {
                alert('Vui lòng nhập User ID hoặc Tên đăng nhập!');
                return;
            }

            var $btn = $(this);
            var $loading = $('#znd_search_loading');
            var $result = $('#znd_user_result_box');

            $btn.prop('disabled', true);
            $loading.show();
            $result.hide();

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'znd_admin_search_user',
                    keyword: keyword
                },
                success: function (res) {
                    $btn.prop('disabled', false);
                    $loading.hide();
                    if (res.success) {
                        $result.html(res.data.html).fadeIn(200);
                    } else {
                        alert(res.data ? res.data.message : 'Không tìm thấy!');
                    }
                },
                error: function () {
                    $btn.prop('disabled', false);
                    $loading.hide();
                    alert('Lỗi kết nối máy chủ!');
                }
            });
        });

        // Bấm Enter trong ô tìm kiếm
        $(document).on('keypress', '#znd_search_user_input', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                $('#znd_search_user_btn').click();
            }
        });

        // Đổi loại vật phẩm trong form cấp tặng
        $(document).on('change', '#znd_grant_type', function () {
            var selectedType = $(this).val();
            var $selectItem = $('#znd_grant_item_id');

            $selectItem.find('option').each(function () {
                if ($(this).data('type') === selectedType) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            // Chọn option đầu tiên đang hiển thị
            $selectItem.find('option[data-type="' + selectedType + '"]').first().prop('selected', true);
        });

        // Cấp tặng vật phẩm
        $(document).on('click', '#znd_grant_submit_btn', function (e) {
            e.preventDefault();
            var $box = $(this).closest('.znd-admin-user-details');
            var userId = $box.data('user-id');
            var type = $('#znd_grant_type').val();
            var itemId = $('#znd_grant_item_id').val();
            var duration = $('#znd_grant_duration').val();

            var $btn = $(this);
            $btn.prop('disabled', true).text('Đang cấp tặng...');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'znd_admin_grant_item',
                    user_id: userId,
                    type: type,
                    item_id: itemId,
                    duration: duration
                },
                success: function (res) {
                    $btn.prop('disabled', false).html('<i class="fa fa-plus-circle"></i> Cấp tặng ngay');
                    if (res.success) {
                        alert(res.data.message);
                        // Tải lại thông tin user
                        $('#znd_search_user_btn').click();
                    } else {
                        alert(res.data ? res.data.message : 'Thất bại!');
                    }
                },
                error: function () {
                    $btn.prop('disabled', false).html('<i class="fa fa-plus-circle"></i> Cấp tặng ngay');
                    alert('Lỗi kết nối máy chủ!');
                }
            });
        });

        // Thu hồi vật phẩm
        $(document).on('click', '.znd-revoke-btn', function (e) {
            e.preventDefault();
            if (!confirm('Bạn có chắc chắn muốn thu hồi vật phẩm này của thành viên không?')) {
                return;
            }

            var $box = $(this).closest('.znd-admin-user-details');
            var userId = $box.data('user-id');
            var type = $(this).data('type');
            var itemId = $(this).data('id');

            var $btn = $(this);
            $btn.prop('disabled', true).text('Đang thu hồi...');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'znd_admin_revoke_item',
                    user_id: userId,
                    type: type,
                    item_id: itemId
                },
                success: function (res) {
                    if (res.success) {
                        alert(res.data.message);
                        $('#znd_search_user_btn').click();
                    } else {
                        $btn.prop('disabled', false).html('<i class="fa fa-trash"></i> Thu hồi');
                        alert(res.data ? res.data.message : 'Thất bại!');
                    }
                },
                error: function () {
                    $btn.prop('disabled', false).html('<i class="fa fa-trash"></i> Thu hồi');
                    alert('Lỗi kết nối máy chủ!');
                }
            });
        });
    });

})(jQuery);
