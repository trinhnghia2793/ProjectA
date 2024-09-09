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
            let value = HT.removeUtf8(input.val())
            $('.canonical').html(BASE_URL + '/' + value + SUFFIX)
        })

        // Chỉnh meta-description theo mô tả SEO (dưới) (meta-description)
        $('textarea[name=meta_description]').on('keyup', function() {
            let input = $(this)
            let value = input.val()
            $('.meta-description').html(value)
        })
    }

    // Hàm remove ký tự UTF-8 có dấu (dùng cho canonical)
    HT.removeUtf8 = (str) => {
        str = str.toLowerCase(); // Chuyển về ký tự viết thường
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|,|\.|\:|\;|\'|\–| |\"|\&|\#|\[|\]|\\|\/|~|$|_/g, "-");
        str = str.replace(/-+-/g, "-");
        str = str.replace(/^\-+|\-+$/g, "");
        return str;
    }


    // Gọi hàm để chạy
    $(document).ready(function() {
        HT.seoPreview();
    })

})(jQuery);