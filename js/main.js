$(document).ready(function () {
    ajaxUpdate();

});
function ajaxUpdate() {
    jQuery('a[data-ajax]').click(function () {
        var obj = $(this);
        var page = obj.attr('href');
        $.ajax({
            url: page,
            dataType: 'json',
            type: "get",
            success: function (data) {
                if ("status" in data && data['status'] && "content" in data) {
                    var div = document.createElement('div');
                    div.innerHTML = data.content;
                    var html = $(div).find("div#ajax-content").html();
                    $('#ajax-content').html(html);
                    obj.addClass('active');
                    ajaxUpdate();
                    return false;
                }
                return false;
            }
        });
        return false;
    });
}
