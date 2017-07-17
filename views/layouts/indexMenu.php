<div class="inner">
    <h3 class="masthead-brand"><?php echo $data['title']; ?></h3>
    <nav>
        <ul class="nav masthead-nav">
            <li class="<?php if ($data['thisPage'] === 'quote') echo 'active' ?>"><a href="/">Карусель</a></li>
            <li class="<?php if ($data['thisPage'] === 'quotes') echo 'active' ?>"><a href="/quotes">Список</a></li>
            <li class="<?php if ($data['thisPage'] === 'authors') echo 'active' ?>"><a href="/authors">Авторы</a></li>           
            <li class="<?php if ($data['thisPage'] === 'offer') echo 'active' ?>"><a href="/offer">Добавить</a></li>
            <li class="<?php if ($data['thisPage'] === 'feedbacks') echo 'active' ?>"><a href="/feedbacks" title="Оставить отзыв"><span class="glyphicon glyphicon glyphicon-comment" aria-hidden="true"></span></a></li>
            <li class="<?php if ($data['thisPage'] === 'about') echo 'active' ?>"><a href="/about" title="О проекте"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></li>
        </ul>
    </nav>
</div>
