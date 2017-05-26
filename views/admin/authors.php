<form class="text-left" action="/admin/authors/addauthor" method="POST">
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
            <a href="/admin/quotes?author_id=<?php echo $author['id']; ?>"><?php echo @$this->html($author['name']); ?></a>
            <span> / </span>
            <a href="/admin/authors/editauthor?author_id=<?php echo $author['id']; ?>">Переименовать</span></a>
            <span> / </span>
            <a href="/admin/authors/delauthor?author_id=<?php echo $author['id']; ?>">Удалить</span></a>
        </li>
    <?php endforeach; ?>
</ul>