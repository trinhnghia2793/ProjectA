(function($) {

    "use strict";
    var HT = {};

    // Hàm hiện seo preview như người dùng nhập vào
    HT.seoPreview = () => {
        // Chỉnh meta-title theo mô tả SEO (trên) (meta-title)
        $('input[name=meta_title]').on('keyup', function() {
            let input = $(this)
            let value = input.val()
            $('.meta-title').html(value)
        })

        // tính độ dài đường link host để căn padding
        $('input[name=canonical]').css({
            'padding-left': parseInt($('.baseUrl').outerWidth()) + 10
        })

        // Chỉnh canonical theo đường dẫn (canonical)
        $('input[name=canonical]').on('keyup', function() {
            let input = $(this)
            let value = input.val()
            $('.canonical').html(BASE_URL + '/' + value + SUFFIX)
        })

        // Chỉnh meta-description theo mô tả SEO (dưới) (meta-description)
        $('textarea[name=meta_description]').on('keyup', function() {
            let input = $(this)
            let value = input.val()
            $('.meta-description').html(value)
        })
    }

    // Gọi hàm để chạy
    $(document).ready(function() {
        HT.seoPreview();
    })

})(jQuery);