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

$('.btn-load-more-comments').click(function(){
    $('.btn-load-more-comments').addClass('running');
    // $.ajax({
    //
    // });
});

$('#trumbowyg-demo').trumbowyg({
    lang: 'ru'
});

$('.btn-send-comment').click(function(){
    var articleId = 'articleId=' + $('input.hidden-article-id').val();
    var userName = 'userName=' + $('input.comment-user-name').val();
    var userEmail = 'userEmail=' + $('input.comment-user-email').val();
    var commentText = 'commentText=' + $('.comment-text').text();
    $.ajax({
        type: 'POST',
        dataType: 'html',
        data: articleId +
        '&' + userName +
        '&' + userEmail +
        '&' + commentText,
        url: '/action/article/save-comment/',
        success: function (data) {
            alert(data);
        }
    });
    return false;
});
