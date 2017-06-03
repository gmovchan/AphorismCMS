<?php require __DIR__ . '/indexHead.php'; ?>    
<div class="site-wrapper">
    <div class="site-wrapper-inner">
        <div class="cover-container">
            <div class="masthead clearfix">
                <div class="inner">
                    <h3 class="masthead-brand">Админ</h3>
                    <nav>
                        <ul class="nav masthead-nav">
                            <li class="<?php if ($data['thisPage'] === 'quotes') echo 'active' ?>"><a href="/admin/quotes">Цитаты</a></li>
                            <li class="<?php if ($data['thisPage'] === 'authors') echo 'active' ?>"><a href="/admin/authors">Авторы</a></li>
                            <li class="<?php if ($data['thisPage'] === 'offers') echo 'active' ?>"><a href="/admin/offer">Предложенные</a></li>
                            <li class="<?php if ($data['thisPage'] === 'addQuote') echo 'active' ?>"><a href="/admin/quote/addquote">Добавить</a></li>
                            <li><a href="/admin/auth/logout" title="Выйти"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="inner cover">
                <!-- Элемент выводит информацию о результате выполнения некоторых скриптов -->
                <?php require __DIR__ . '/../notice/notice.php'; ?>
            </div>
            <div class="inner cover">
                <?php require_once __DIR__ . '/../' . $content_view ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
