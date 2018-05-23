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
                form.find('.empty-input').css({'border-color':'#c91700'});
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
                var sizeEmpty = form.find('.empty-input').length;
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
                    loadingOverlayStart();
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
                        url: '/wde-master/admin/action/article/save/',
                        success: function (articleId) {
                            loadingOverlayStop();
                            $('input.article-id').val(articleId);
                            $.alert({
                                icon: 'far fa-check-circle',
                                title: 'Success!',
                                content: 'Изменения успешно сохранены!',
                                type: 'green',
                                typeAnimated: true,
                                buttons: {
                                    close: {
                                        text: 'close',
                                        btnClass: 'btn-green'
                                    }
                                }
                            });
                        },
                        error: function() {
                            loadingOverlayStop();
                            $.alert({
                                icon: 'fas fa-exclamation-circle',
                                title: 'Error!',
                                content: 'Ajax broken. Try later!',
                                type: 'red',
                                typeAnimated: true,
                                buttons: {
                                    close: {
                                        text: 'close',
                                        btnClass: 'btn-red'
                                    }
                                }
                            });
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
                icon: 'fas fa-exclamation-circle',
                title: 'Удаление статьи!',
                content: 'Вы уверены что хотите удалить статью???',
                type: 'orange',
                typeAnimated: true,
                buttons: {
                    confirm: {
                        text: 'confirm',
                        btnClass: 'btn-red',
                        action: function () {
                            loadingOverlayStart();
                            var articleId = 'articleId=' + $('input.article-id').val();
                            $.ajax({
                                type: 'POST',
                                dataType: 'html',
                                data: articleId,
                                url: '/wde-master/admin/action/article/delete/',
                                success: function (data) {
                                    loadingOverlayStop();
                                    $.alert({
                                        icon: 'far fa-check-circle',
                                        title: 'Delete success!',
                                        content: 'Статья успешно удалена!',
                                        type: 'orange',
                                        typeAnimated: true,
                                        buttons: {
                                            close: {
                                                text: 'close',
                                                btnClass: 'btn-orange'
                                            }
                                        }
                                    });
                                    window.location.href = '/wde-master/admin/articles/'
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'cancel',
                        btnClass: 'btn-orange'
                    }
                }
            });
            return false;
        });
    });

    $(function(){
        var seoTitle = $('.seo-title');
        var lengthSeoTitle = $('.length-seo-title');
        seoTitle.keyup(function(){
            textLength = seoTitle.val().length;
            resultLength = 80 - textLength;
            lengthSeoTitle.text(resultLength);
        });
    });

    $(function(){
        var seoDescription = $('.seo-description');
        var lengthSeoDescription = $('.length-seo-description');
        seoDescription.keyup(function(){
            textLength = seoDescription.val().length;
            resultLength = 280 - textLength;
            lengthSeoDescription.text(resultLength);
        });
    });

    $(function(){
        $('.btn-add-notice-definition').click(function(){
            var containerNotice = '&lt;div class=&quot;notice-definition&quot;&gt;' +
                '&lt;i class=&quot;fas fa-exclamation-circle&quot;&gt;&lt;/i&gt;' +
                '&lt;/div&gt;';
            $('textarea.textarea-text-article').froalaEditor('html.insert', containerNotice, false);
            return false;
        });
    });

    $(function(){
        $('.btn-add-code').click(function(){
            var containerCode = '&lt;pre&gt;&lt;/pre&gt;';
            $('textarea.textarea-text-article').froalaEditor('html.insert', containerCode, false);
            return false;
        });
    });

    $(function(){
        $('.btn-add-console').click(function(){
            var containerConsole = '&lt;pre class=&quot;console&quot;&gt;&lt;/pre&gt;';
            $('textarea.textarea-text-article').froalaEditor('html.insert', containerConsole, false);
            return false;
        });
    });
});