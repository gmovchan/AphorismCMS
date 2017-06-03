<?php require __DIR__ . '/indexHead.php'; ?>
<div class="site-wrapper">
    <div class="site-wrapper-inner-middle">
        <div class="cover-container">
            <div class="masthead-middle clearfix">
                <!-- Пункты меню -->
                <?php require __DIR__ . '/indexMenu.php'; ?>
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
