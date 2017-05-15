<?php
if (isset($data['errors'])) {
    require __DIR__ . '/../errors/errorsList.php';
}

if (isset($data['successful'])) {
    require __DIR__ . '/../successful/successfulList.php';
    // если автор успешно добавлен, то поля формы останутся пустыми
    $_POST['authorName'] = NULL;
}
?>
<br>
<div class="inner cover">
    <form class="text-left" action="/admin/authorAdd" method="POST">
        <div class="input-group">
            <input type="text" class="form-control" aria-label="..." name="authorName" value="<?php echo @$this->html($_POST['authorName']); ?>">
            <div class="input-group-btn">
                <button type="submit" class="btn btn-default">Добавить имя</button>
            </div>
        </div>
    </form>
<br>
<ul class="list-group authors">
    <?php foreach ($data['authors'] as $author): ?>   
        <li class="list-group-item">
            <span class="badge"><?php echo $author['countQuotes']; ?></span>
            <?php echo $author['name']; ?>
        </li>
    <?php endforeach; ?>
</ul>
</div>