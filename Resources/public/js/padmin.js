$(function() {
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
});
