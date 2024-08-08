(function($) {

    "use strict";
    var HT = {};

    // Hàm setup CkEditor4
    HT.setupCkeditor = () => {
        if($('.ck-editor')) {
            $('.ck-editor').each(function() {
                let editor = $(this)
                let elementId = editor.attr('id')
                HT.ckeditor4(elementId)
            })
        }
    }

    HT.ckeditor4 = (elementId, elementHeight) => {
        if(typeof(elementHeight) == 'undefined'){
            elementHeight = 500;
        }
        CKEDITOR.replace( elementId, {
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


    // Hàm upload ảnh dùng CkFinder2
    HT.uploadImageToInput = () => {
        $('.upload-image').click(function() {
            let input = $(this)
            let type = input.attr('data-type')
            HT.setupCkFinder2(input, type);
        })
    }

    // Hàm setup CkFinder2
    HT.setupCkFinder2 = (object, type) => {
        if(typeof(type) == 'undefined') {
            type = 'Images';
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function( fileUrl, data ) {
            object.val(fileUrl)
            // object.val('/' + fileUrl)
        }
        finder.popup();
    }

    $(document).ready(function() {
        HT.uploadImageToInput();
        HT.setupCkeditor();
    })

})(jQuery);