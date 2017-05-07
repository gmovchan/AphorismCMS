<div class="bg-danger">Произошли ошибки:
    <ul>
        <?php if (!is_null($error)):
            foreach ($error as $key => $value): ?>
                <li><?php echo $value; ?></li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

