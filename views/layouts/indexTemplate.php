<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="Сборник цитат и афоризмов знаменитых философов, писателей, художников и учёных о пользе холостого образа жизни.">
        <meta name="author" content="">
        <link rel="icon" href="<?php echo $data['publicDir']; ?>/img/favicon.ico">

        <title><?php echo $data['title']; ?></title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo $data['publicDir']; ?>/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <link href="<?php echo $data['publicDir']; ?>/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="<?php echo $data['publicDir']; ?>/css/cover.css" rel="stylesheet">

        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
        <script src="<?php echo $data['publicDir']; ?>/assets/js/ie-emulation-modes-warning.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?php echo $data['publicDir']; ?>/assets/js/vendor/jquery.min.js"><\/script>')</script>
        <script src="<?php echo $data['publicDir']; ?>/dist/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="<?php echo $data['publicDir']; ?>/assets/js/ie10-viewport-bug-workaround.js"></script>
    </head>

    <body>

        <!-- Yandex.Metrika counter -->
        <script src="<?php echo $data['publicDir']; ?>/js/yandex_metrica.js"></script>
        <!-- /Yandex.Metrika counter -->

        <div class="site-wrapper">
            <div class="site-wrapper-inner">
                <div class="cover-container">
                    <div class="masthead clearfix">
                        <!-- Пункты меню -->
                        <?php require __DIR__ . '/../menu/indexMenu.php'; ?>
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
