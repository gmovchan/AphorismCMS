<?php
var_dump($_POST);
if (isset($data['errors'])) {
    require __DIR__ . '/../errors/errorsList.php';
}

if (isset($data['successful'])) {
    require __DIR__ . '/../successful/successfulList.php';
    // если автор успешно добавлен, то поле "authorName" будет пустым
    unset($_POST);
}
?>

<div class="inner cover">
    <h1 class="cover-heading">Новая цитата</h1>
    <p>Поле с текстом цитаты необходимо обязательно заполнить. Все остальные поля - по желанию.</p>
    <form class="text-left" action="/admin/addquote" method="POST">
        <div class="form-group">
            <label for="quoteText">Текст</label>
            <textarea name="quoteText" class="form-control" rows="6"><?php echo @$this->html($_POST['quoteText']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="authorQuote">Автор</label>
            <select class="form-control" name="authorQuoteID">
                <?php foreach ($data['authors'] as $author): ?>
                    <?php //сохраняет выбранным элемент списка в случае ошибки после отправки формы ?>
                    <?php if (isset($_POST['authorQuoteID'])): ?>
                        <?php if ($_POST['authorQuoteID'] == $author['id']): ?>
                            <option selected value="<?php echo $author['id']; ?>"><?php echo $author['name']; ?></option>
                        <?php else: ?>
                            <option value="<?php echo $author['id']; ?>"><?php echo $author['name']; ?></option>
                        <?php endif; ?>

                    <?php else: ?>
                        <option value="<?php echo $author['id']; ?>"><?php echo $author['name']; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
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

