$(document).ready(function () {
    $(document).pjax('a[data-pjax]', '#pjax-content');
    $(document).on('submit', 'form', function(event) {
        $.pjax.submit(event, '#pjax-content');
    });
    // ajaxUpdate();

});
