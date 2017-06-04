<!-- Скрипт управления с клавиатуры -->
<script src="<?php echo $data['publicDir']; ?>/js/random.js"></script>
<?php if ($data['quote']): ?>
    <div class="panel panel-default panel-quote" id="quote<?php echo $data['quote']['quote_id']; ?>">
        <div class="panel-body">
            <blockquote class="text-left">
                <p><?php echo $this->html($data['quote']['text']); ?></p>
                <footer>
                    <a href="/quotes?author_id=<?php echo $data['quote']['author_id']; ?>"><cite title="<?php echo $this->html($data['quote']['author']); ?>"><?php echo $this->html($data['quote']['author']); ?></cite></a>
                </footer>
            </blockquote>
            <?php require __DIR__ . '/repostQuote.php'; ?>    
        </div>       
        <div class="panel-footer">
            <!--
            <a href="/quotes#quote<?php echo $data['quote']['quote_id']; ?>">id<?php echo $data['quote']['quote_id']; ?></a>
            <span> / </span>
            -->
            <a href="/quote/comments?quote_id=<?php echo $data['quote']['quote_id']; ?>">Комментировать (<?php echo $data['quote']['amountComments']; ?>)</a>
        </div>
    </div>
<?php endif; ?>
<p class="lead ">
    <?php
    if ($data['quote']['previous_id'] === 0) {
        echo '<button id="previous-quote" class="btn btn btn-default disabled" type="button" title="Клавиатура: A, ←"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></button>';
    } else {
        echo '<button id="previous-quote" class="btn btn btn-default" type="button" onclick="self.location.href=\'/quote?quote_id=' . $data['quote']['previous_id'] . '\';" title="Клавиатура: A, ←"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></button>';
    }
    ?>

    <button id="random-quote-btn" class="btn btn btn-default" type="button" onclick="self.location.href = '/quote?quote_id=<?php echo $data['quote']['random_id']; ?>';" title="Клавиатура: R"><span class="glyphicon glyphicon-random" aria-hidden="true"></span></button>

    <?php
    if ($data['quote']['next_id'] === 0) {
        echo '<button id="next-quote" class="btn btn btn-default disabled" type="button" title="Клавиатура: D, →"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></button>';
    } else {
        echo '<button id="next-quote" class="btn btn btn-default" type="button" onclick="self.location.href=\'/quote?quote_id=' . $data['quote']['next_id'] . '\';" title="Клавиатура: D, →"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></button>';
    }
    ?>
</p>