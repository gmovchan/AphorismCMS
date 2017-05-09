$(document).ready(function ()
{
    // включает красивые подсказки
    $('[data-toggle="tooltip"]').tooltip()
    
    function clickBtn(id) {
        $(id).click();
    }

    $(document).keydown(function (e)
    {
        console.log(e.which);
        switch (e.which) {

            // влево
            case 65:
                clickBtn('#previous-quote');
                break;

                // вправо
            case 68:
                clickBtn('#next-quote');
                break;

                // влево
            case 37:
                clickBtn('#previous-quote');
                break;

                // вправо
            case 39:
                clickBtn('#next-quote');
                break;

                // рандом
            case 82:
                clickBtn('#random-quote');
                break;

            default:

                break;
        }
    })
}
)