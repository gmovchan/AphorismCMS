<?php
if (isset($data['errors'])) {
    require __DIR__ . '/../errors/errorsList.php';
    var_dump($data);
}
?>
<div class="inner cover">
    <div class="form-header">
        <h1 class="cover-heading">Изменить цитату</h1>
        <p>Поле с текстом цитаты необходимо обязательно заполнить. Все остальные поля - по желанию.</p>
    </div>
    <form class="text-left" action="/admin/quoteSaveChanges" method="POST">
        <div class="form-group">
            <label for="quoteText">Текст</label>
            <textarea name="quoteText" class="form-control" rows="6"><?php echo @$this->html($data['quote']['text']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="authorQuote">Автор</label>
            <select class="form-control" name="authorQuoteID">
                <?php foreach ($data['authors'] as $author): ?>
                    <?php //сохраняет выбранным элемент списка в случае ошибки после отправки формы ?>
                    <?php if (isset($data['quote']['author_id'])): ?>
                        <?php if ($data['quote']['author_id'] == $author['id']): ?>
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
            <input name="sourceQuote" type="text" class="form-control" placeholder="" value="<?php echo @$this->html($data['quote']['source']); ?>">
        </div>
        <div class="form-group">
            <label for="creatorQuote">Создал</label>
            <input name="creatorQuote" type="text" class="form-control" placeholder="" value="<?php echo @$this->html($data['quote']['creator']); ?>">
        </div>
        <!-- скрытое поле для передачи id сохраняемой цитаты -->
        <input style="display: none;" name="quoteID" value="<?php echo @$this->html($data['quote']['quote_id']); ?>">
        <button type="submit" class="btn btn-default">Сохранить</button>
    </form>
</div>

