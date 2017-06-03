$(document).ready(function ()
{
    // показывает скрытую часть текста цитаты
    $('.get-full-text').click(function () {
        dataId = $(this).attr('data-id');  
        elementId = '#end-text-id' + dataId;
        $(elementId).show();
        $(this).remove();
    })
}
)

