jQuery(document).ready(function ($) {
    $('.commentlist li').each(function (i) {
        $(this).find('div.commentNumber').text('#' + (i + 1));
    });

    $('#commentform').on('click', '#submit', function (e) {
         e.preventDefault();

         var commentBtn = $(this);
         var commentForm = $('#commentform');
         var wrapResult = $('.wrap_result');

        wrapResult
            .css('color', 'green')
            .text('Cохранение комментария')
            .fadeIn(500, function () {
                var data = commentForm.serializeArray();
                $.ajax({
                    url : commentForm.attr('action'),
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    datatype: 'JSON',
                    success: function (html) {
                        if(html.error) {
                            wrapResult
                                .css('color', 'red')
                                .append('<br /><strong>Ошибка: ' + html.error.join('<br />') + '</strong>');
                            wrapResult
                                .delay(2000)
                                .fadeOut(500);
                        }
                        else if(html.success) {
                            wrapResult
                                .append('<br /><strong>Сохранено</strong>')
                                .delay(2000)
                                .fadeOut(500, function () {
                                    if(html.data.parent_id > 0) {
                                        commentBtn
                                            .parents('div#respond')
                                            .prev()
                                            .after('<ul class="children">' + html.comment + '</ul>');
                                    }
                                    else {
                                        if($("#comments .commentlist").html() !== null) {
                                            $('ol.commentlist').append(html.comment);
                                        }
                                        else {
                                            $('#respond').before('<ol class="commentlist group">' + html.comment + '</ol>');
                                        }
                                    }

                                    $('#cancel-comment-reply-link').click();
                                })
                        }
                    },
                    error: function () {
                        wrapResult
                            .css('color', 'red')
                            .append('<br /><strong>Ошибка!</strong>');
                        wrapResult
                            .delay(2000)
                            .fadeOut(500, function () {
                                $('#cancel-comment-reply-link').click();
                            })
                    }
                })
            });

    });
});