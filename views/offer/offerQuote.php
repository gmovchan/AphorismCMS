<?php
if (isset($error)) {
    require __DIR__ . '/../errors/errorsList.php';
}
?>
<div class="inner cover">
    <h1 class="cover-heading">Новая цитата</h1>
    <p>Поле с текстом цитаты необходимо обязательно заполнить. Все остальные поля - по желанию.</p>
    <form class="text-left" action="/offer/addOffer" method="POST">
        <div class="form-group">
            <label for="quoteText">Текст</label>
            <textarea name="quoteText" class="form-control" rows="6" id="quoteText"></textarea>
        </div>
        <div class="form-group">
            <label for="authorQuote">Автор</label>
            <input name="authorQuote" type="text" class="form-control" id="authorQuote" placeholder="Автор">
        </div>
        <div class="form-group">
            <label for="sourceQuote">Источник</label>
            <input name="sourceQuote" type="text" class="form-control" id="sourceQuote" placeholder="Источник">
        </div>
        <div class="form-group">
            <label for="comment">Комментарий для администратора</label>
            <textarea name="comment" class="form-control" rows="3" id="comment"></textarea>
        </div>
        <div class="form-group">
            <label for="authorOffer">Ваш никнейм или ссылка на профиль</label>
            <input name="authorOffer" type="text" class="form-control" id="authorOffer" placeholder="Представьтесь">
        </div>
        <button type="submit" class="btn btn-default">Отправить</button>
    </form>
</div>

