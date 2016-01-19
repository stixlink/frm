$(document).ready(function () {
    jQuery('a.pagination-item').click(function () {
        var obj = $(this);
        var page = obj.attr('data-page');
        $.ajax({
            url: '/news',
            dataType: 'json',
            type: "get",
            data: {'page': page},
            success: function (data) {
                if ("status" in data && data['status'] && "content" in data) {
                    var div = document.createElement('div');
                    div.innerHTML = data.content;
                    var html = $(div).find("div#news-list").html();
                    $('#news-list').html(html);
                    $('a.pagination-item').removeClass('active');
                    obj.addClass('active');
                    return false;
                }
                return false;
            }
        });
        return false;
    });
});
