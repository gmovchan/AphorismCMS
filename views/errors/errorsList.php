<div class="bg-danger errors">Произошли ошибки:
    <ul>
        <?php if (!is_null($data['errors'])):
            foreach ($data['errors'] as $key => $value): ?>
                <li><?php echo $value; ?></li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

