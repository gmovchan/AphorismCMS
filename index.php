<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="/favicon.ico">

        <title>Холостяки</title>

        <!-- Bootstrap core CSS -->
        <link href="/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <link href="/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="cover.css" rel="stylesheet">

        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
        <script src="/assets/js/ie-emulation-modes-warning.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>

        <div class="site-wrapper">

            <div class="site-wrapper-inner">

                <div class="cover-container">

                    <div class="masthead clearfix">
                        <div class="inner">
                            <h3 class="masthead-brand">Знаменитые холостяки</h3>
                            <nav>
                                <ul class="nav masthead-nav">
                                    <li class="active"><a href="#">Цитаты</a></li>
                                    <li><a href="#">Список</a></li>
                                    <li><a href="#">Предложить</a></li>
                                    <li><a href="#">Контакты</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>

                    <div class="inner cover">
                        <h1 class="cover-heading">Цитата</h1>
                        <p class="lead">
                            <?php
                            require_once 'quotes.php';
                            ?>
                        <blockquote class="text-left">
                            <p><?php echo $quote['text']; ?></p>
                            <footer><a href="?author_id=<?php echo $quote['author_id']; ?>"><cite title="<?php echo $quote['author']; ?>"><?php echo $quote['author']; ?></cite></a></footer>
                        </blockquote>
                        </p>
                        <p class="lead">
                            <?php
                            if ($quote['previous_id'] === 0) {
                                echo '<a href="#" class="btn btn-lg btn-default disabled" title="Предыдущая цитата"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>';
                            } else {
                                echo '<a href="?quote_id='. $quote['previous_id'] .'" class="btn btn-lg btn-default" title="Предыдущая цитата"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>';
                            }
                            ?>
                            <a href="?quote_id=<?php echo $quote['random_id']; ?>" class="btn btn-lg btn-default" title="Случайная цитата"><span class="glyphicon glyphicon-random" aria-hidden="true"></span></a>
                            <a href="#" class="btn btn-lg btn-default" title="Обсудить"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
                            <?php
                            if ($quote['next_id'] === 0) {
                                echo '<a href="#" class="btn btn-lg btn-default disabled" title="Предыдущая цитата"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>';
                            } else {
                                echo '<a href="?quote_id='. $quote['next_id'] .'" class="btn btn-lg btn-default" title="Предыдущая цитата"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>';
                            }
                            ?>
                        </p>
                    </div>

                    <div class="mastfoot">
                        <div class="inner">
                            <p>Cover template for <a href="http://getbootstrap.com">Bootstrap</a>, by <a href="https://twitter.com/mdo">@mdo</a>.</p>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="/assets/js/vendor/jquery.min.js"><\/script>')</script>
        <script src="/dist/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="/assets/js/ie10-viewport-bug-workaround.js"></script>
    </body>
</html>
