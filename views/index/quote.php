<div itemscope itemtype="http://schema.org/CreativeWork">
    <?php if ($data['quote']): ?>
        <div class="panel panel-default panel-quote" id="quote<?php echo $data['quote']['quote_id']; ?>">
            <div class="panel-body">
                <blockquote class="text-left">
                    <p><span itemprop="description"><?php echo $this->html($data['quote']['text']); ?></span></p>
                    <footer>
                        <span itemprop="author"><a href="/quotes?author_id=<?php echo $data['quote']['author_id']; ?>"><cite title="<?php echo $this->html($data['quote']['author']); ?>"><?php echo $this->html($data['quote']['author']); ?></cite></a></span>
                    </footer>
                </blockquote>
                <?php require __DIR__ . '/repostQuote.php'; ?>
            </div>
            <div class="panel-footer">
                <a href="/quote/comments?quote_id=<?php echo $data['quote']['quote_id']; ?>">id<?php echo $data['quote']['quote_id']; ?></a>
            </div>
        </div>
    <?php endif; ?>
    <?php require_once __DIR__ . '/quoteComments.php'; ?>
</div>



