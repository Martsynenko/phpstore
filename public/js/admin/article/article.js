$(document).ready(function() {

    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };

        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    function showPopupSuccessMessage() {
        $('#overlay').fadeIn(400, // снaчaлa плaвнo пoкaзывaем темную пoдлoжку
            function(){ // пoсле выпoлнения предъидущей aнимaции
                $('#popup-success-notice')
                    .css('display', 'block') // убирaем у мoдaльнoгo oкнa display: none;
                    .animate({opacity: 1, top: '50%'}, 200); // плaвнo прибaвляем прoзрaчнoсть oднoвременнo сo съезжaнием вниз
            });
    }

    /* Зaкрытие мoдaльнoгo oкнa, тут делaем тo же сaмoе нo в oбрaтнoм пoрядке */
    $('#overlay').click( function(){ // лoвим клик пo крестику или пoдлoжке
        $('#popup-success-notice')
            .animate({opacity: 0, top: '45%'}, 200,  // плaвнo меняем прoзрaчнoсть нa 0 и oднoвременнo двигaем oкнo вверх
                function(){ // пoсле aнимaции
                    $(this).css('display', 'none'); // делaем ему display: none;
                    $('#overlay').fadeOut(400); // скрывaем пoдлoжку
                }
            );
    });

    /* Зaкрытие мoдaльнoгo oкнa, тут делaем тo же сaмoе нo в oбрaтнoм пoрядке */
    $('.btn-close-modal').click( function(){ // лoвим клик пo крестику или пoдлoжке
        $('#popup-success-notice')
            .animate({opacity: 0, top: '45%'}, 200,  // плaвнo меняем прoзрaчнoсть нa 0 и oднoвременнo двигaем oкнo вверх
                function(){ // пoсле aнимaции
                    $(this).css('display', 'none'); // делaем ему display: none;
                    $('#overlay').fadeOut(400); // скрывaем пoдлoжку
                }
            );
    });


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

    $(function() {
        $('.wide').niceSelect();
        $('.nice-select').css({
            'width': '120px',
            'border-radius': '2px',
            'float': 'none',
            'height': '37px',
            'line-height': '35px',
            'padding-left': '15px'
        });
        $('.nice-select .list').css({
            'border-radius': '2px'
        });
    });

    // TEXT EDITOR
    $(function() {
        $('.wsy-editor').froalaEditor({
            height: 500
        });
    });

    $(function() {
        // $('.btn-article-preview').magnificPopup();
        $('.btn-article-preview').on('click', function () {
            $('span.tag-article').remove();
            $('.content-preview-article br').remove();
            var titleArticle = $('.input-title-article').val();
            var textArticle = $('.textarea-text-article').val();
            var tagsArticle = $('.input-tags-article').val();
            var arrTags = tagsArticle.split(' ');


            $('.content-preview-article h1').text(titleArticle);
            $('.content-preview-article p.preview-article-text').html(textArticle);
            var j = 0;
            $.each(arrTags, function (i, val) {
                if (j == 10) {
                    $('.preview-article-tags-title').append('<br>');
                }
                $('.preview-article-tags-title').append('<span class="tag-article">' + val + '</span>');
                j++;
            });
            $('span.tag-article').detach(":empty");
        }).magnificPopup();
    });

    $(function() {
        $('.form-edit-article').each(function(){
            var form = $(this);
            var btnSave = form.find('.btn-article-save');

            form.find('.ifield').addClass('empty-input');

            function lightEmpty(){
                form.find('.empty-input').css({'border-color':'#ff473a'});
                // Через полсекунды удаляем подсветку
                setTimeout(function(){
                    form.find('.empty-input').removeAttr('style');
                },10000);
            }

            function checkInput(){
                form.find('.ifield').each(function(){
                    if($(this).val() != ''){
                        // Если поле не пустое удаляем класс-указание
                        $(this).removeClass('empty-input');
                    } else {
                        // Если поле пустое добавляем класс-указание
                        $(this).addClass('empty-input');
                    }
                });
            }

            btnSave.click(function(){
                checkInput();
                var sizeEmpty = form.find('.empty-input').size();
                // Вешаем условие-тригер на кнопку отправки формы
                if(sizeEmpty > 0){
                    if(!btnSave.hasClass('disabled')){
                        btnSave.addClass('disabled')
                    }
                } else {
                    btnSave.removeClass('disabled')
                }
                if($(this).hasClass('disabled')){
                    // подсвечиваем незаполненные поля и форму не отправляем, если есть незаполненные поля
                    lightEmpty();
                    return false;
                } else {
                    var articleUrl = 'articleUrl=' + $('input.seo-url').val();
                    var articleId = 'articleId=' + $('input.article-id').val();
                    var seoTitle = 'seoTitle=' + $('input.seo-title').val();
                    var seoDescription = 'seoDescription=' + $('input.seo-description').val();
                    var seoKeywords = 'seoKeywords=' + $('input.seo-keywords').val();
                    var statusArticle = 'statusArticle=' + $('select.status-article').val();
                    var titleArticle = 'titleArticle=' + $('input.input-title-article').val();
                    var textArticle = 'textArticle=' +  encodeURIComponent($('textarea.textarea-text-article').froalaEditor('html.get'));
                    var tagsArticle = 'tagsArticle=' + $('input.input-tags-article').val();
                    $.ajax({
                        type: 'POST',
                        dataType: 'html',
                        data: articleUrl +
                        '&' + articleId +
                        '&' + seoTitle +
                        '&' + seoDescription +
                        '&' + seoKeywords +
                        '&' + statusArticle +
                        '&' + titleArticle +
                        '&' + textArticle +
                        '&' + tagsArticle,
                        url: '/wde-master/admin/article/save',
                        success: function (articleId) {
                            $('input.article-id').val(articleId);
                            showPopupSuccessMessage();
                        }
                    });
                }
                return false;
            });
        });
    });

    $(function(){
        var btnDelete = $('.btn-article-delete');

        btnDelete.click(function(){
            $.confirm({
                title: 'Удаление статьи!',
                content: 'Вы уверены что хотите удалить статью???',
                buttons: {
                    confirm: function () {
                        var articleId = 'articleId=' + $('input.article-id').val();
                        $.ajax({
                            type: 'POST',
                            dataType: 'html',
                            data: articleId,
                            url: '/wde-master/admin/article/delete',
                            success: function (data) {
                                $.alert({
                                    title:'Alert!',
                                    content:'Article delete success'
                                });
                                window.location.href = '/wde-master/admin/articles'
                            }
                        });
                    },
                    cancel: function () {
                    }
                }
            });
            return false;
        });
    });
});