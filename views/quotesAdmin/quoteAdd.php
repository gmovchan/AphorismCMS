<?php
if (isset($data['errors'])) {
    require __DIR__ . '/../errors/errorsList.php';
}
?>
<div class="inner cover">
    <h1 class="cover-heading">Новая цитата</h1>
    <p>Поле с текстом цитаты необходимо обязательно заполнить. Все остальные поля - по желанию.</p>
    <form class="text-left" action="/admin/quoteAdd" method="POST">
        <div class="form-group">
            <label for="quoteText">Текст</label>
            <textarea name="quoteText" class="form-control" rows="6"><?php echo @$this->html($_POST['quoteText']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="authorQuote">Автор</label>
            <input name="authorQuote" type="text" class="form-control" placeholder="Автор" value="<?php echo @$this->html($_POST['authorQuote']); ?>">
        </div>
        <div class="form-group">
            <label for="sourceQuote">Источник</label>
            <input name="sourceQuote" type="text" class="form-control" placeholder="Источник" value="<?php echo @$this->html($_POST['sourceQuote']); ?>">
        </div>
        <div class="form-group">
            <label for="creatorQuote">Создал</label>
            <input name="creatorQuote" type="text" class="form-control" placeholder="Представьтесь" value="<?php echo $data['login'] ?>">
        </div>
        <button type="submit" class="btn btn-default">Добавить</button>
    </form>
</div>

