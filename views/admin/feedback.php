<div> 
    <?php if (empty($data['comments'])): ?>
        <div class="form-header" id="no-feedbecks-msg">
            <p>Ещё нет отзывов.</p>
        </div>
    <?php endif; ?>
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
                <div class="panel-footer">
                    <a href="/admin/feedbacks/delcomment?comment_id=<?php echo $comment['id']; ?>">Удалить</a>
                </div>
            </div>
        </span>
    <?php endforeach; ?>
</div>

