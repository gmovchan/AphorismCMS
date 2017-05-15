<div class="inner cover">
    <div class="form-header">
        <h1 class="cover-heading">Изменить цитату</h1>
        <p>Поле с текстом цитаты необходимо обязательно заполнить. Все остальные поля - по желанию.</p>
    </div>
    <form class="text-left" action="/admin/addquote" method="POST">
        <div class="form-group">
            <label for="quoteText">Текст</label>
            <textarea name="quoteText" class="form-control" rows="6"><?php echo @$this->html($data['quote']['quoteText']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="authorQuote">Автор</label>
            <select class="form-control" name="authorQuoteID">
                <?php foreach ($data['authors'] as $author): ?>
                    <?php //сохраняет выбранным элемент списка в случае ошибки после отправки формы ?>
                    <?php if (isset($data['quote']['authorQuoteID'])): ?>
                        <?php if ($data['quote']['authorQuoteID'] == $author['id']): ?>
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
            <input name="sourceQuote" type="text" class="form-control" placeholder="Источник" value="<?php echo @$this->html($data['quote']['sourceQuote']); ?>">
        </div>
        <div class="form-group">
            <label for="creatorQuote">Создал</label>
            <input name="creatorQuote" type="text" class="form-control" placeholder="Представьтесь" value="<?php echo @$this->html($data['quote']['creatorQuote']); ?>">
        </div>
        <button type="submit" class="btn btn-default">Сохранить</button>
    </form>
</div>

