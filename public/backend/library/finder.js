(function($) {

    "use strict";
    var HT = {};

    // Hàm setup CkEditor4
    HT.setupCkeditor = () => {
        if($('.ck-editor')) {
            $('.ck-editor').each(function() {
                let editor = $(this)
                let elementId = editor.attr('id')
                let elementHeight = editor.attr('data-height')
                HT.ckeditor4(elementId, elementHeight)
            })
        }
    }

    // Hàm Ckeditor4
    HT.ckeditor4 = (elementId, elementHeight) => {

        if(typeof(elementHeight) == 'undefined'){
            elementHeight = 500;
        }

        CKEDITOR.replace( elementId, {
            // autoUpdateElement: false,
            height: elementHeight,
            removeButtons: '',
            entities: true,
            allowedContent: true,
            toolbarGroups: [
                { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker','undo' ] },
                { name: 'links' },
                { name: 'insert' },
                { name: 'forms' },
                { name: 'tools' },
                { name: 'document',    groups: [ 'mode', 'document', 'doctools'] },
                { name: 'others' },
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup','colors','styles','indent'  ] },
                { name: 'paragraph',   groups: [ 'list', '', 'blocks', 'align', 'bidi' ] },
            ],
            removeButtons: 'Save,NewPage,Pdf,Preview,Print,Find,Replace,CreateDiv,SelectAll,Symbol,Block,Button,Language',
            removePlugins: "exportpdf",
        
        });
    }


    // HT.ckeditor4 = (elementId) => {
    //     CKEDITOR.replace( elementId, {
    //         height: elementHeight,
    //         removeButtons: '',
    //         entities: true,
    //         allowedContent: true,
    //         toolbarGroups: [
    //             { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
    //             { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
    //             { name: 'links' },
    //             { name: 'insert' },
    //             { name: 'forms' },
    //             { name: 'tools' },
    //             { name: 'document',    groups: [ 'mode', 'document', 'doctools'] },
    //             { name: 'colors' },
    //             { name: 'others' },
    //             '/',
    //             { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    //             { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
    //             { name: 'styles'}
    //         ],
    //         // removeButtons: 'Save,NewPage,Pdf,Preview,Print,Find,Replace,CreateDiv,SelectAll,Symbol,Block,Button,Language',
    //         // removePlugins: "exportpdf",
        
    //     });
    // }


    // Hàm upload ảnh vào input dùng CkFinder2
    HT.uploadImageToInput = () => {
        $('.upload-image').click(function() {
            let input = $(this)
            let type = input.attr('data-type')
            HT.setupCkFinder2(input, type);
        })
    }

    // Hàm upload ảnh avatar (khi click vào thực hiện mở popup để chọn avatar)
    HT.uploadImageAvatar = () => {
        $('.image-target').click(function() {
            let input = $(this)
            let type = 'Images'
            HT.browseServerAvatar(input, type)
        })
    }

    // Upload nhiều hình ảnh
    HT.multipleUploadImageCkeditor = () => {
        $(document).on('click', '.multipleUploadImageCkeditor', function(e) {
            let object = $(this)
            let target = object.attr('data-target') // cái này lấy tên của id để truyền ảnh vào á
            HT.browseServerCkeditor(object, 'Images', target);

            e.preventDefault()
        })
    }

    // Hàm upload ảnh vào album
    HT.uploadAlbum = () => {
        $(document).on('click', '.upload-picture', function(e) {
            HT.browseServerAlbum();
            e.preventDefault();
        })
    }

    // Hàm setup CkFinder2 khi chọn vào vùng ảnh đại diện
    HT.setupCkFinder2 = (object, type) => {
        if(typeof(type) == 'undefined') {
            type = 'Images';
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function( fileUrl, data ) {
            object.val(fileUrl)
        }
        finder.popup();
    }

    // Hàm mở popup duyệt ảnh để lấy avatar
    HT.browseServerAvatar = (object, type) => {
        if(typeof(type) == 'undefined') {
            type = 'Images';
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function( fileUrl, data ) {
            object.find('img').attr('src', fileUrl)
            object.siblings('input').val(fileUrl)
        }
        finder.popup();
    }

    // Hàm mở popup thực hiện multipleUpload
    HT.browseServerCkeditor = (object, type, target) => {

        if(typeof(type) == 'undefined') {
            type = 'Images';
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function( fileUrl, data, allFiles ) {
            var html = '';
            for(var i = 0; i < allFiles.length; i++) {
                var image = allFiles[i].url
                html += '<div class="image-content"><figure>'
                    html += '<img src="'+image+'" alt="'+image+'">'
                    html += '<figcaption>Nhập vào mô tả cho ảnh</figcaption>'
                html += '</figure></div>';

            }
            
            CKEDITOR.instances[target].insertHtml(html)
        }
        finder.popup();
    }
    
    // Hàm mở popup thêm ảnh vào album
    HT.browseServerAlbum = () => {
        var type = 'Images';
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function( fileUrl, data, allFiles ) {
            var html = '';
            for(var i = 0; i < allFiles.length; i++) {
                var image = allFiles[i].url
                
                html += '<li class="ui-state-default">'
                    html += '<div class="thumb">'
                        html += '<span class="span image img-scaledown">'
                            html += '<img src="'+image+'" alt="'+image+'">'
                            html += '<input type="hidden" name="album[]" value="'+image+'">'
                        html += '</span>'
                        html += '<button class="delete-image"><i class="fa fa-trash"></i></button>'
                    html += '</div>'
                html += '</li>'

            }
         
            $('.click-to-upload').addClass('hidden')
            $('#sortable').append(html)
            $('.upload-list').removeClass('hidden')
            
        }
        finder.popup();
    }

    // Xóa hình đã chọn trước đó
    HT.deletePicture = () => {
        $(document).on('click', '.delete-image', function() {
            let _this = $(this)
            _this.parents('.ui-state-default').remove()
            // Kiểm tra nếu xóa tất cả hình trong album thì cho hiện cái kia lên lại
            if($('.ui-state-default').length == 0) {
                $('.click-to-upload').removeClass('hidden')
                $('.upload-list').addClass('hidden')
            }
        })
    }

    // Gọi hàm để chạy
    $(document).ready(function() {
        HT.uploadImageToInput();
        HT.setupCkeditor();
        HT.uploadImageAvatar();
        HT.multipleUploadImageCkeditor();
        HT.uploadAlbum();
        HT.deletePicture();
    })

})(jQuery);