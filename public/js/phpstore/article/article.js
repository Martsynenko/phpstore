$.LoadingOverlaySetup({
    color           : "rgba(0, 0, 0, 0.4)",
    maxSize         : "25px",
    minSize         : "25px",
    resizeInterval  : 0,
    size            : "25%"
});

$(document)
    .ajaxStart(function () {
        $.LoadingOverlay('show', {
            image       : "",
            fontawesome : "fas fa-circle-notch fa-spin"
        }, 60000);
    })
    .ajaxStop(function () {
        $.LoadingOverlay('hide');
    });

// NEW COMMENT
$('.btn-new-comment').click(function(){
    var btnNewComment = $('.btn-new-comment');
    var BtnValue = Number(btnNewComment.val());
    if (BtnValue === 0) {
        $('.new-comment').css('display', 'block');
        $('.arrow-down').removeClass('fa-angle-up').addClass('fa-angle-down');
        btnNewComment.text('Скрыть форму');
        btnNewComment.val(1);
    } else if (BtnValue === 1) {
        var textButton = 'Оставить комментарий';
        var widthWindow = $(window).width();
        if (widthWindow < 480) {
            textButton = 'Оставить';
        }
        $('.new-comment').css('display', 'none');
        $('.arrow-down').removeClass('fa-angle-down').addClass('fa-angle-up');
        btnNewComment.text(textButton);
        btnNewComment.val(0);
    }
});