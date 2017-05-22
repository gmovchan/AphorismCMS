<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="<?php echo $data['publicDir']; ?>favicon.ico">

        <title>Администрирование</title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo $data['publicDir']; ?>dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <link href="<?php echo $data['publicDir']; ?>assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="<?php echo $data['publicDir']; ?>css/cover.css" rel="stylesheet">

        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
        <script src="<?php echo $data['publicDir']; ?>assets/js/ie-emulation-modes-warning.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?php echo $data['publicDir']; ?>assets/js/vendor/jquery.min.js"><\/script>')</script>
        <script src="<?php echo $data['publicDir']; ?>dist/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="<?php echo $data['publicDir']; ?>assets/js/ie10-viewport-bug-workaround.js"></script>
    </head>

    <body>
        <div class="masthead clearfix">
            <div class="inner">
                <h3 class="masthead-brand">Администрирование</h3>
                <nav>
                    <ul class="nav masthead-nav">
                        <li class="<?php if ($data['thisPage'] === 'quotesAdmin') echo 'active' ?>"><a href="/admin/quotes">Цитаты</a></li>
                        <li class="<?php if ($data['thisPage'] === 'authorsAdmin') echo 'active' ?>"><a href="/admin/authors">Авторы</a></li>
                        <li class="<?php if ($data['thisPage'] === 'offersAdmin') echo 'active' ?>"><a href="/admin/offers">Предложенные</a></li>
                        <li class="<?php if ($data['thisPage'] === 'addQuote') echo 'active' ?>"><a href="/admin/addquote">Добавить</a></li>
                        <li><a href="/auth/logout" title="Выйти"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="site-wrapper">

            <div class="site-wrapper-inner">

                <div class="cover-container">
                    <br>
                    <div class="inner cover">
                        <?php require __DIR__ . '/../notice/notice.php'; ?>
                    </div>
                    <?php require_once __DIR__ . '/../' . $content_view ?>

                </div>

            </div>

        </div>
        <div class="mastfoot">
            <div class="inner">
                <p>Cover template for <a href="http://getbootstrap.com">Bootstrap</a>, by <a href="https://twitter.com/mdo">@mdo</a>.</p>
            </div>
        </div>
    </body>
</html>
