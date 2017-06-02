$(document).ready(function ()
{
    // включает красивые подсказки
    $('[data-toggle="tooltip"]').tooltip()

    function clickBtn(id) {
        $(id).click();
    }

    $(document).keydown(function (e)
    {
        //console.log(e.which);
        switch (e.which) {

            case 65:
                // влево
                clickBtn('#previous-quote');
                break;

            case 68:
                // вправо
                clickBtn('#next-quote');
                break;

            case 37:
                // влево
                clickBtn('#previous-quote');
                break;

            case 39:
                // вправо
                clickBtn('#next-quote');
                break;
                
            case 82:
                // рандом
                clickBtn('#random-quote-btn');
                break;

            default:

                break;
        }
    })
}
)