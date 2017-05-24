<div class="inner cover">
    <div class="form-header">
        <h1 class="cover-heading">Изменить имя автора</h1>
    </div>
    <form class="text-left" method="POST" action="/admin/authors/editauthor">
        <div class="form-group">
            <label for="authorName">Имя</label>
            <input name="authorName" type="text" class="form-control" value="<?php echo @$this->html($data['author']['name']); ?>">
        </div>
        <!-- скрытое поле для передачи id сохраняемой цитаты -->
        <input style="display: none;" name="idInDB" value="<?php echo $data['author']['id']; ?>">
        <button type="submit" class="btn btn-default">Сохранить</button>
    </form>
</div>

