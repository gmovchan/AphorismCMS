<!-- Скрипт для кнопок -->
<script src="<?php echo $data['publicDir']; ?>/js/quotes.js"></script>
<?php foreach ($data['quotes'] as $qoute): ?>
    <div class="panel panel-default panel-quote" id="quote<?php echo $qoute['quote_id']; ?>">
        <div class="panel-body">
            <blockquote class="text-left">
                <p>
                    <?php if ($qoute['lengthExceeded'] === true): ?>
                    <span class="startText"><?php echo $this->html($qoute['startText']); ?></span><button data-id="<?php echo $qoute['quote_id']; ?>" type="button" class="btn btn-link get-full-text">читать далее...</button><span class="endText" id="end-text-id<?php echo $qoute['quote_id']; ?>"><?php echo $this->html($qoute['endText']); ?></span>
                    <?php else: ?>
                        <?php echo $this->html($qoute['text']); ?>
                    <?php endif; ?>
                    
                </p>
                <footer>
                    <a href="/quotes?author_id=<?php echo $qoute['author_id']; ?>"><cite title="<?php echo $this->html($qoute['author']); ?>"><?php echo $this->html($qoute['author']); ?></cite></a>
                </footer>
            </blockquote>
        </div>
        <div class="panel-footer">
            <a href="/quotes#quote<?php echo $qoute['quote_id']; ?>">id<?php echo $qoute['quote_id']; ?></a>
            <span> / </span>
            <a href="/admin/quote/editquote?quote_id=<?php echo $qoute['quote_id']; ?>">Изменить</a>
            <span> / </span>
            <a href="/admin/quote/comments?quote_id=<?php echo $qoute['quote_id']; ?>">Комментарии (<?php echo $qoute['amountComments']; ?>)</a>
            <span> / </span>
            <a href="/admin/quote/delquote?quote_id=<?php echo $qoute['quote_id']; ?>">Удалить</a>
        </div>
    </div>
<?php endforeach; ?>