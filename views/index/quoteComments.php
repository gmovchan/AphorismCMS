<!-- Скрипт для кнопок -->
<script src="<?php echo $data['publicDir']; ?>/js/quote.js"></script>
<button class="btn btn-default" type="button" id="open-comment-form">Оставить комментарий</button>        
<form class="form-horizontal" action="/quote/comments?quote_id=<?php echo @$this->html($data['quote']['quote_id']); ?>" method="POST" id="comment-form">
    <div class="form-group">
        <label for="name" class="col-sm-1 control-label">Имя</label>
        <div class="col-sm-10">
            <div class="input-group">
                <input name="name" type="text" class="form-control" placeholder="" value="<?php echo @$this->html($_POST['name']); ?>">
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-default">Отправить</button>
                </span>
            </div><!-- /input-group -->
        </div>
    </div>
    <div class="form-group">
        <label for="comment" class="col-sm-1 control-label">Пост</label>
        <div class="col-sm-10">
            <textarea name="comment" class="form-control" rows="6"><?php echo @$this->html($_POST['comment']); ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="comment" class="col-sm-1 control-label">Каптча</label>
        <div class="col-sm-10 captcha">
            <img src="/quote/getcaptchaimg" id="" onclick="this.src='/quote/getcaptchaimg?rid=' + Math.random();" title="Обновить картинку" class="captcha-img">
            <input type="text" name="captcha" class="form-control" placeholder="Введите код с картинки">
        </div>
    </div>
    <input style="display: none;" name="idInDB" value="<?php echo @$this->html($data['quote']['quote_id']); ?>">
</form>
<?php foreach ($data['comments'] as $comment): ?>
    <span itemprop="comment">
        <div class="panel panel-default comment">
            <div class="panel-body">
                <p>
                    <?php echo $this->html($comment['author_name']); ?>
                    <span> / </span>
                    <?php echo "{$comment['timeArray']['day']}.{$comment['timeArray']['month']}.{$comment['timeArray']['year']}"; ?>
                    <span> / </span>
                    <?php echo "{$comment['timeArray']['day']}:{$comment['timeArray']['day']}"; ?>
                </p>
                <p><?php echo $this->html($comment['comment_text']); ?></p>
            </div>
        </div>
    </span>
<?php endforeach; ?>