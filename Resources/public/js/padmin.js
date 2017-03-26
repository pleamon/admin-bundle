$(function() {
    function bytesToSize(bytes) {
        if (bytes === 0) return '0 B';
            var k = 1024,
            sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
            i = Math.floor(Math.log(bytes) / Math.log(k));
        return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
    }
    $("select").select2();
    $(".delete-action").on("submit", function(e) {
        e.preventDefault();
        swal({
            title: "确定要删除吗",
            text: "删除后将无法找回",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "取消",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认删除",
            closeOnConfirm: false
        }, function(isConfirm) {
            if(isConfirm) {
                setTimeout(function() {
                    e.target.submit();
                }, 1000);
                swal("您选择的内容即将删除", "", "success");
            }
        });
    });
    $(".file-show-btn").on("click", function(e) {
        $(this).children()[0].click();
    })
    $(".upload-file-btn").on("change", function(e) {
        var _this = this;
        var file = this.files[0];
        var reader = new FileReader();
        reader.readAsDataURL(file);
        a = reader;
        var sReg = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;
        reader.onload = function(e){
            $(_this).parents('.fileupload').find('.filename').html('文件名: <code>' + file.name + '</code>');
            $(_this).parents('.fileupload').find('.filesize').html('文件大小: <code>' + bytesToSize(file.size) + '</code>');
            $(_this).parents('.fileupload').find('.filetype').html('文件类型: <code>' + file.type + '</code>');
            if(sReg.test(file.type)) {
                $(_this).parents('.fileupload').find('img')[0].src = e.target.result;
            }
        }
    });

    filelist = Array();
    $(".upload-multiple-file-btn").on("change", function(e) {
        var _this = this;
        filelist = Array();
        $.each(this.files, function(k, file) {
            filelist.push(file);

            var html = '<li class="list-group-item"> <a href="javascript:;" class="badge cancel-upload-file"><i class="fa fa-times"></i></a><span class="badge badge-danger"></span> <i class="fa fa-file"></i> ' + file.name + ' <code>' + bytesToSize(file.size) + '</code> <code>' + file.type + '</code> </li>'
            $(_this).parents('.fileupload').find('.filelist').append(html);
        })
    });
    $(".filelist").delegate(".cancel-upload-file", "click", function(e) {
        var index = $(".filelist .cancel-upload-file").index(this);
        var li = $(".filelist li")[index];
        $(li).fadeOut(100, function() {
            this.remove();
            filelist.splice(index, 1);
        });;
    });


    /*
    $('.table').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    });
    */

    $(".modal").on("hidden.bs.modal", function() {
        $(this).removeData("bs.modal");
    });

    function formatState (state) {
        if (!state.id) { return state.text; }
        var $state = $(
            '<span><i class="' + state.text + '"></i> ' + state.text + '</span>'
        );
        return $state;
    };

    $(".icon_type").select2({
        templateResult: formatState,
        templateSelection: formatState
    });

    $(".summernote").summernote({
        dialogsInBody: true 
    });
});
