<div class="container">

    <form action="" method="post" class="form-signin">
        <h2 class="form-signin-heading">Пожалуйста войдите</h2>
        <?php
            require __DIR__ . '/../notice/notice.php';
        ?>
        <div class="form-group">
            <label for="name">Логин</label>
            <input type="text" class="form-control" name="login" placeholder="Логин" value="<?php echo @$this->html($data['login']); ?>">
        </div>
        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" class="form-control" placeholder="Пароль" name="password">
        </div>
        <input class="hidden" name="send" value="send">
        <button type="submit" class="btn btn-default">Войти</button>
    </form>    
</div>