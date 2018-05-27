// NEW COMMENT

var isValidName = false;
var isValidEmail = false;
var isValidText = false;

function isEmpty( el ){
    return !$.trim(el.html())
}

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
    var btnLoadComments = $('.btn-load-more-comments');
    if (btnLoadComments.hasClass('running')) {
        return false;
    }
    btnLoadComments.addClass('running');
    var countComments = 'countComments=' + $('.comments-content-item').length;
    var articleId = 'articleId=' + $('.current-article-id').val();
    $.ajax({
        type: 'POST',
        dataType: 'html',
        data: countComments +
            '&' + articleId,
        url: '/action/article/load-comments/',
        success: function(data) {
            btnLoadComments.removeClass('running');
            var objData = $.parseJSON(data);
            if (objData.status == 'success') {
                $.each(objData.comments, function(key, item){
                    $('.comments-content').append('<div class="comments-content-item">\n' +
                        '<img src="/public/img/no-avatar.png" alt="no-avatar"/>\n' +
                        '<span class="comment-title-user-name">' + item.name + '</span>\n' +
                        '<i class="fas fa-ellipsis-h"></i>\n' +
                        '<span class="comment-time">' + item.date + '</span>\n' +
                        '<p>' + item.comment + '</p>\n' +
                        '</div>');
                });
                if (!objData.showBtnLoadComments) {
                    btnLoadComments.css('display', 'none');
                }
            }
        },
        error: function () {
            alert('Что то пошло не так!!');
        }
    });
});

$('#trumbowyg-demo').trumbowyg({
    lang: 'ru'
});

$('.btn-send-comment').click(function(){
    $('.notice-new-comment-success').css({
        'display' : 'none'
    });
    $('.notice-new-comment-error').css({
        'display' : 'none'
    });
    if (isValidName && isValidEmail && isValidText) {
        loadingOverlayStart();
        var articleId = 'articleId=' + $('input.hidden-article-id').val();
        var url = 'url=' + $('input.hidden-current-url').val();
        var userName = 'userName=' + $('input.comment-user-name').val();
        var userEmail = 'userEmail=' + $('input.comment-user-email').val();
        var commentText = 'commentText=' + $('.comment-text').text();
        $.ajax({
            type: 'POST',
            dataType: 'html',
            data: articleId +
            '&' + url +
            '&' + userName +
            '&' + userEmail +
            '&' + commentText,
            url: '/action/article/save-comment/',
            success: function (data) {
                var objData = $.parseJSON(data);
                loadingOverlayStop();
                if (objData.status == 'success') {
                    $('.notice-new-comment-success p').text(objData.message);
                    $('.notice-new-comment-success').css({
                        'display' : 'block'
                    });
                    $('.comments-content').prepend('<div class="comments-content-item">\n' +
                        '<img src="/public/img/no-avatar.png" alt="no-avatar"/>\n' +
                        '<span class="comment-title-user-name">' + objData.data.name + '</span>\n' +
                        '<i class="fas fa-ellipsis-h"></i>\n' +
                        '<span class="comment-time">' + objData.data.date + '</span>\n' +
                        '<p>' + objData.data.comment + '</p>\n' +
                        '</div>');
                    var currentCount = $('.title-comments span.count').text();
                    currentCount = +currentCount + 1;
                    $('.title-comments span.count').text(currentCount);
                }
                if (objData.status == 'error') {
                    $('.notice-new-comment-error p').text(objData.message);
                    $('.notice-new-comment-error').css({
                        'display' : 'block'
                    });
                }
                if (objData.status == 'verification') {
                    $('.notice-new-comment-success').css({
                        'display' : 'block'
                    });
                }
            }
        });
    } else {
        var emptyValue = 'Поле не может быть пустым';
        if (!isValidName && !$('.comment-user-name').val()) {
            $('.form-error-message-name').text(emptyValue).css({
                'transition': 'opacity 2s cubic-bezier(0.08, 0.86, 1, 1)',
                'opacity' : '1',
                'height' : 'auto'
            });
            $('.comment-user-name').css({
                'border' : '1px solid rgba(205, 2, 2, 0.71)'
            });
        }
        if (!isValidEmail && !$('.comment-user-email').val()) {
            $('.form-error-message-email').text(emptyValue).css({
                'transition': 'opacity 2s cubic-bezier(0.08, 0.86, 1, 1)',
                'opacity' : '1',
                'height' : 'auto'
            });
            $('.comment-user-email').css({
                'border' : '1px solid rgba(205, 2, 2, 0.71)'
            });
        }
        if (!isValidText) {
            $('.form-error-message-text').text(emptyValue).css({
                'transition': 'opacity 2s cubic-bezier(0.08, 0.86, 1, 1)',
                'opacity' : '1',
                'height' : 'auto',
                'margin-bottom' : '-10px'
            });
        }
    }
    return false;
});

$('.comment-user-name').keyup(function(){
    isValidName = true;
    var errorInputName = $('.form-error-message-name');

    var regexp = /^([а-яА-Я]|[a-zA-Z])*$/;

    var emptyValue = 'Поле не может быть пустым';
    var errorName = 'Введите настоящее имя';

    var valueName = $(this).val();

    if (!valueName) {
        isValidName = false;
        errorInputName.text(emptyValue);
    } else if (!regexp.exec(valueName)) {
        isValidName = false;
        errorInputName.text(errorName);
    }

    if (!isValidName) {
        errorInputName.css({
            'transition': 'opacity 2s cubic-bezier(0.08, 0.86, 1, 1)',
            'opacity' : '1',
            'height' : 'auto'
        });
        $(this).css({
            'border' : '1px solid rgba(205, 2, 2, 0.71)'
        });
    } else {
        errorInputName.css({
            'opacity' : '0',
            'height' : '0'
        }).text();
        $(this).css({
            'border' : '1px solid rgba(94, 186, 72, 0.94)'
        });
    }

    if (valueName) {
        $('.title-comments .new-comment .btn-clear-name').css({
            'display' : 'inline'
        });
    } else {
        $('.title-comments .new-comment .btn-clear-name').css({
            'display' : 'none'
        });
    }

    isValid = isValidName;
});

$('.comment-user-email').keyup(function(){
    isValidEmail = true;
    var errorInputEmail = $('.form-error-message-email');

    var regexp = /^\w+@\w+\.\w{2,4}$/i;

    var emptyValue = 'Поле не может быть пустым';
    var errorName = 'Введите настоящий email адрес';

    var valueName = $(this).val();

    if (!valueName) {
        isValidEmail = false;
        errorInputEmail.text(emptyValue);
    } else if (!regexp.exec(valueName)) {
        isValidEmail = false;
        errorInputEmail.text(errorName);
    }

    if (!isValidEmail) {
        errorInputEmail.css({
            'transition': 'opacity 2s cubic-bezier(0.08, 0.86, 1, 1)',
            'opacity' : '1',
            'height' : 'auto'
        });
        $(this).css({
            'border' : '1px solid rgba(205, 2, 2, 0.71)'
        });
    } else {
        errorInputEmail.css({
            'opacity' : '0',
            'height' : '0'
        }).text();
        $(this).css({
            'border' : '1px solid rgba(94, 186, 72, 0.94)'
        });
    }

    if (valueName) {
        $('.title-comments .new-comment .btn-clear-email').css({
            'display' : 'inline'
        });
    } else {
        $('.title-comments .new-comment .btn-clear-email').css({
            'display' : 'none'
        });
    }

    isValid = isValidEmail;
});

$('.comment-text').keyup(function(){
    isValidText = true;
    var errorInputText = $('.form-error-message-text');

    var emptyValue = 'Напишите свой комментарий...';

    var valueName = $(this).text();

    if (!valueName) {
        isValidText = false;
        errorInputText.text(emptyValue);
    }

    if (!isValidText) {
        errorInputText.css({
            'transition': 'opacity 2s cubic-bezier(0.08, 0.86, 1, 1)',
            'opacity' : '1',
            'height' : 'auto',
            'margin-bottom' : '-10px'
        });
    } else {
        errorInputText.css({
            'opacity' : '0',
            'height' : '0'
        }).text();
    }
});

$('.title-comments .new-comment .wrap-clear-btn-name').click(function(){
    $('.comment-user-name').val('').focus();
});

$('.title-comments .new-comment .wrap-clear-btn-email').click(function(){
    $('.comment-user-email').val('').focus();
});
