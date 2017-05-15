<!-- Скрипт управления с клавиатуры -->
<script src="<?php echo $data['publicDir']; ?>/js/random.js"></script>

<div class="inner cover">
    <!--<h1 class="cover-heading">Цитата</h1>-->
    <p class="lead"></p>
    <div class="panel panel-default">
        <div class="panel-body">
            <blockquote class="text-left">
                <p><?php echo $this->html($data['quote']['text']); ?></p>
                <footer><a href="?author_id=<?php echo $data['quote']['author_id']; ?>"><cite data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->html($data['quote']['author']); ?>"><?php echo $this->html($data['quote']['author']); ?></cite></a></footer>
            </blockquote>
        </div>
    </div>

    <p class="lead ">

        <?php
        if ($data['quote']['previous_id'] === 0) {
            echo '<button id="previous-quote" class="btn btn btn-default disabled" type="button" data-toggle="tooltip" data-placement="bottom" title="A, ←"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></button>';
        } else {
            echo '<button id="previous-quote" class="btn btn btn-default" type="button" onclick="self.location.href=\'/random?quote_id=' . $data['quote']['previous_id'] . '\';" data-toggle="tooltip" data-placement="bottom" title="A, ←"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></button>';
        }
        ?>
        <button id="random-quote" class="btn btn btn-default" type="button" onclick="self.location.href = '/random?quote_id=<?php echo $data['quote']['random_id']; ?>';" data-toggle="tooltip" data-placement="bottom" title="R"><span class="glyphicon glyphicon-random" aria-hidden="true"></span></button>
        <button id="comment-quote" class="btn btn btn-default" type="button" onclick="self.location.href = '?comment=<?php echo $data['quote']['quote_id']; ?>';" data-toggle="tooltip" data-placement="bottom" title="C"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></button>

        <?php
        if ($data['quote']['next_id'] === 0) {
            echo '<button id="next-quote" class="btn btn btn-default disabled" type="button" data-toggle="tooltip" data-placement="bottom" title="D, →"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></button>';
        } else {
            echo '<button id="next-quote" class="btn btn btn-default" type="button" onclick="self.location.href=\'/random?quote_id=' . $data['quote']['next_id'] . '\';" data-toggle="tooltip" data-placement="bottom" title="D, →"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></button>';
        }
        ?>
    </p>
</div>



