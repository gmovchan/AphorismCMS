<?php foreach ($data['quotes'] as $qoute): ?>
    <div class="panel panel-default panel-quote" id="quote<?php echo $qoute['quote_id']; ?>">
        <div class="panel-body">
            <blockquote class="text-left">
                <p><?php echo $this->html($qoute['text']); ?></p>
                <footer>
                    <a href="/quotes?author_id=<?php echo $qoute['author_id']; ?>"><cite title="<?php echo $this->html($qoute['author']); ?>"><?php echo $this->html($qoute['author']); ?></cite></a>
                </footer>
            </blockquote>
        </div>
        <div class="panel-footer">
            <a href="/quotes#quote<?php echo $qoute['quote_id']; ?>">id<?php echo $qoute['quote_id']; ?></a>
            <span> / </span>
            <a href="/quote/comments?quote_id=<?php echo $qoute['quote_id']; ?>">Комментарии (<?php echo $qoute['amountComments']; ?>)</a>
        </div>
    </div>
<?php endforeach; ?>

