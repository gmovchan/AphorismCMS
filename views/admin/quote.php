<?php if ($data['quote']): ?>
    <div class="panel panel-default panel-quote" id="quote<?php echo $data['quote']['quote_id']; ?>">
        <div class="panel-body">
            <blockquote class="text-left">
                <p><?php echo $this->html($data['quote']['text']); ?></p>
                <footer>
                    <a href="/quotes?author_id=<?php echo $data['quote']['author_id']; ?>"><cite title="<?php echo $this->html($data['quote']['author']); ?>"><?php echo $this->html($data['quote']['author']); ?></cite></a>
                </footer>
            </blockquote>
        </div>
        <div class="panel-footer">
            <a href="/quotes#quote=<?php echo $data['quote']['quote_id']; ?>">id<?php echo $data['quote']['quote_id']; ?></a>
            <span> / </span>
            <a href="/admin/quote/editquote?quote_id=<?php echo $data['quote']['quote_id']; ?>">Изменить</a>
            <span> / </span>
            <a href="/admin/quote/delquote?quote_id=<?php echo $data['quote']['quote_id']; ?>">Удалить</a>
        </div>
    </div>
<?php endif; ?>
<?php if (!empty($data['comments'])): ?>
    <h3 class="white-text">Комментарии</h3>
<?php endif; ?>
<?php foreach ($data['comments'] as $comment): ?>
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
        <div class="panel-footer">
            <a href="/admin/quote/delcomment?comment_id=<?php echo $comment['id']; ?>&quote_id=<?php echo $data['quote']['quote_id']; ?>">Удалить</a>
        </div>
    </div>
<?php endforeach; ?>



