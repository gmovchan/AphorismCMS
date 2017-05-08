<div class="inner cover">
    <!--<h1 class="cover-heading">Цитата</h1>-->
    <p class="lead"></p>
    <blockquote class="text-left">
        <p><?php echo $this->html($data['quote']['text']); ?></p>
        <footer><a href="?author_id=<?php echo $data['quote']['author_id']; ?>"><cite title="<?php echo $this->html($data['quote']['author']); ?>"><?php echo $this->html($data['quote']['author']); ?></cite></a></footer>
    </blockquote>
    <p class="lead">
        <?php
        if ($data['quote']['previous_id'] === 0) {
            echo '<a href="#" class="btn btn-lg btn-default disabled" title="Предыдущая цитата"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>';
        } else {
            echo '<a href="/random?quote_id=' . $data['quote']['previous_id'] . '" class="btn btn-lg btn-default" title="Предыдущая цитата"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>';
        }
        ?>
        <a href="/random?quote_id=<?php echo $data['quote']['random_id']; ?>" class="btn btn-lg btn-default" title="Случайная цитата"><span class="glyphicon glyphicon-random" aria-hidden="true"></span></a>
        <a href="?comment=<?php echo $data['quote']['quote_id']; ?>" class="btn btn-lg btn-default" title="Обсудить"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
            <?php
            if ($data['quote']['next_id'] === 0) {
                echo '<a href="#" class="btn btn-lg btn-default disabled" title="Предыдущая цитата"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>';
            } else {
                echo '<a href="/random?quote_id=' . $data['quote']['next_id'] . '" class="btn btn-lg btn-default" title="Предыдущая цитата"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>';
            }
            ?>
    </p>
</div>


