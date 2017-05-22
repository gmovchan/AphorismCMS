<div class="inner cover">
    <div class="form-header">
        <h1 class="cover-heading">Изменить предложение</h1>
        <p>Поле с текстом цитаты необходимо обязательно заполнить. Все остальные поля - по желанию.</p>
    </div>
    <form class="text-left" method="POST">
        <div class="form-group">
            <label for="quoteText">Текст</label>
            <textarea name="quoteText" class="form-control" rows="6"><?php echo @$this->html($data['offer']['quote_text']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="authorQuote">Предлагаемый автор</label>
            <input name="authorQuote" type="text" class="form-control" value="<?php echo @$this->html($data['offer']['author_quote']); ?>">
        </div>
        <div class="form-group">
            <label for="authorQuoteID">Автор</label>
            <select class="form-control" name="authorQuoteID">
                <?php foreach ($data['authors'] as $author): ?>
                    <?php //сохраняет выбранным элемент списка в случае ошибки после отправки формы ?>
                    <?php if (isset($data['offer']['author_id'])): ?>

                        <?php if ($_POST['authorQuoteID'] == $author['id']): ?>
                            <option selected value="<?php echo $author['id']; ?>"><?php echo $author['name']; ?></option>
                        <?php else: ?>
                            <option value="<?php echo $author['id']; ?>"><?php echo $author['name']; ?></option>
                        <?php endif; ?>

                    <?php else: ?>
                        <?php //по умолчанию автор "низвестен" ?>
                        <?php $_POST['authorQuoteID'] = 157 ?>

                        <?php if ($_POST['authorQuoteID'] == $author['id']): ?>
                            <option selected value="<?php echo $author['id']; ?>"><?php echo $author['name']; ?></option>
                        <?php else: ?>
                            <option value="<?php echo $author['id']; ?>"><?php echo $author['name']; ?></option>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="sourceQuote">Источник</label>
            <input name="sourceQuote" type="text" class="form-control" placeholder="" value="<?php echo @$this->html($data['offer']['source_quote']); ?>">
        </div>
        <div class="form-group">
            <label for="creatorQuote">Создал</label>
            <input name="creatorQuote" type="text" class="form-control" placeholder="" value="<?php echo @$this->html($data['offer']['author_offer']); ?>">
        </div>
        <div class="form-group">
            <label for="comment">Комментарий</label>
            <textarea name="comment" class="form-control" rows="6"><?php echo @$this->html($data['offer']['comment']); ?></textarea>
        </div>
        <!-- скрытое поле для передачи id сохраняемой цитаты -->
        <input style="display: none;" name="idInDB" value="<?php echo @$this->html($data['offer']['id']); ?>">
        <button type="submit" formaction="/admin/saveoffer" class="btn btn-default">Сохранить</button>
        <button type="submit" formaction="/admin/approveoffer" class="btn btn-default">Опубликовать</button>
    </form>
</div>

