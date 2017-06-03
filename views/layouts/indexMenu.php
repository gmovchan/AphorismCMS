<div class="inner">
    <h3 class="masthead-brand"><?php echo $data['title']; ?></h3>
    <nav>
        <ul class="nav masthead-nav">
            <li class="<?php if ($data['thisPage'] === 'quotes') echo 'active' ?>"><a href="/quotes">Цитаты</a></li>
            <li class="<?php if ($data['thisPage'] === 'authors') echo 'active' ?>"><a href="/authors">Авторы</a></li>
            <li class="<?php if ($data['thisPage'] === 'quote') echo 'active' ?>"><a href="/quote">Рандом</a></li>
            <li class="<?php if ($data['thisPage'] === 'offer') echo 'active' ?>"><a href="/offer">Предложить</a></li>
            <li class="<?php if ($data['thisPage'] === 'about') echo 'active' ?>"><a href="/about" title="О сайте"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></li>
        </ul>
    </nav>
</div>
