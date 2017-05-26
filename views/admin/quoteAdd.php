<div class="form-header">
    <h1 class="cover-heading">Добавить цитату</h1>
    <p>* — обязательные для заполнения поля</p>
</div>
<form class="text-left" action="/admin/quote/addquote" method="POST">
    <div class="form-group">
        <label for="quoteText">Текст цитаты *</label>
        <textarea name="quoteText" class="form-control" rows="6"><?php echo @$this->html($_POST['quoteText']); ?></textarea>
    </div>
    <div class="form-group">
        <label for="authorQuote">Автор</label>
        <select class="form-control" name="authorQuoteID">
            <?php foreach ($data['authors'] as $author): ?>
                <?php //сохраняет выбранным элемент списка в случае ошибки после отправки формы ?>
                <?php if (isset($_POST['authorQuoteID'])): ?>

                    <?php if ($_POST['authorQuoteID'] == $author['id']): ?>
                        <option selected value="<?php echo $author['id']; ?>"><?php echo @$this->html($author['name']); ?></option>
                    <?php else: ?>
                        <option value="<?php echo $author['id']; ?>"><?php echo @$this->html($author['name']); ?></option>
                    <?php endif; ?>

                <?php else: ?>
                    <?php //по умолчанию автор "низвестен" ?>
                    <?php $_POST['authorQuoteID'] = 157 ?>
                    <?php if ($_POST['authorQuoteID'] == $author['id']): ?>
                        <option selected value="<?php echo $author['id']; ?>"><?php echo @$this->html($author['name']); ?></option>
                    <?php else: ?>
                        <option value="<?php echo $author['id']; ?>"><?php echo @$this->html($author['name']); ?></option>
                    <?php endif; ?>
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

