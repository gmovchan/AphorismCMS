<?php
  function showTree($folder, $space) {
    /* Получаем полный список файлов и каталогов внутри $folder */
    $files = scandir($folder);
    foreach($files as $file) {
      /* Отбрасываем текущий и родительский каталог */
      if (($file == '.') || ($file == '..')) continue;
      $f0 = $folder.'/'.$file; //Получаем полный путь к файлу
      /* Если это директория */
      if (is_dir($f0)) {
        /* Выводим, делая заданный отступ, название директории */
        echo $space.$file."<br />";
        /* С помощью рекурсии выводим содержимое полученной директории */
        showTree($f0, $space.'&nbsp;&nbsp;');
      }
      /* Если это файл, то просто выводим название файла */
      else echo $space.$file."<br />";
    }
  }
  /* Запускаем функцию для текущего каталога */
  showTree("./", "");
?>

C:\Users\grigo\Code\unmarried>tree /f
Folder PATH listing for volume SSD 128 Gb
Volume serial number is 4808-986B
C:.
│   .gitignore
│   .htaccess
│   composer.json
│   getTree.php
│   index.php
│   README.md
│
├───app
│   ├───AdminControllers
│   │       AdminController.php
│   │       AuthController.php
│   │       AuthorsController.php
│   │       OfferController.php
│   │       QuoteController.php
│   │       QuotesController.php
│   │
│   ├───Configs
│   │       app.ini
│   │       app_example.ini
│   │
│   ├───Core
│   │       AppException.php
│   │       Config.php
│   │       Controller.php
│   │       Errors.php
│   │       Model.php
│   │       Mysql.php
│   │       Request.php
│   │       Route.php
│   │       View.php
│   │
│   ├───IndexControllers
│   │       AboutController.php
│   │       AuthorsController.php
│   │       OfferController.php
│   │       QuoteController.php
│   │       QuotesController.php
│   │
│   └───Models
│           AuthModel.php
│           AuthorsModel.php
│           CommentsModel.php
│           OfferModel.php
│           QuotesModel.php
│
├───nbproject
│   
│
├───public
│   │   favicon.ico
│   │
│   ├───css
│   │       cover.css
│   │       signin.css
│   │
│   │
│   └───js
│           quote.js
│           random.js
│
├───storage
├───support_data
│   │   app.ini
│   │   code_review.txt
│   │   db_auth.sql
│   │   mysql.php
│   │   parser.php
│   │   quotes.php
│   │   unmarried.sql
│   │
│   └───doc
│           great_bachelors.txt
│           quotes.json
│           quotes.txt
│
├───vendor
│
│
└───views
    ├───admin
    │       addQuote.php
    │       authors.php
    │       editAuthor.php
    │       editOffer.php
    │       editQuote.php
    │       offers.php
    │       quote.php
    │       quotes.php
    │
    ├───auth
    │       authForm.php
    │       successfulAuth.php
    │
    ├───errors
    │       error404.php
    │       error503.php
    │
    ├───index
    │       about.php
    │       authors.php
    │       offerQuote.php
    │       quote.php
    │       quotes.php
    │       randomQuote.php
    │
    ├───layouts
    │       adminTemplate.php
    │       authTemplate.php
    │       indexMiddleTemplate.php
    │       indexTemplate.php
    │
    ├───menu
    │       indexMenu.php
    │
    └───notice
            errorsList.php
            notice.php
            successfulList.php