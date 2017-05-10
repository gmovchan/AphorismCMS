<div class="inner cover">
    <p class="lead"></p>
    <?php foreach ($data['quotes'] as $qoute): ?>
        <a href="/quotes#quote<?php echo $qoute['quote_id']; ?>" id="quote<?php echo $qoute['quote_id']; ?>"><?php echo $qoute['quote_id']; ?></a>
        <blockquote class="text-left">
            <p><?php echo $this->html($qoute['text']); ?></p>
            <footer>
                <a href="?author_id=<?php echo $qoute['author_id']; ?>"><cite title="<?php echo $this->html($qoute['author']); ?>"><?php echo $this->html($qoute['author']); ?></cite></a>
            </footer>
        </blockquote>
    <?php endforeach; ?>
</div>


