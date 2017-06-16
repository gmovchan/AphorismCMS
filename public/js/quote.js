$(document).ready(function ()
{
    // скрывает и показывает форму для постинка комментариев
    $('#comment-form').hide();

    $('#open-comment-form').click(function () {
        $('#comment-form').toggle();
        // ловушка для ботов в форме добавления комментария
        $("#email").toggle();

        if ($('#comment-form').is(':visible')) {
            $('#open-comment-form').text('Скрыть форму');
        }

        if ($('#comment-form').is(':hidden')) {
            $('#open-comment-form').text('Оставить комментарий');
        }
    })
}
)

