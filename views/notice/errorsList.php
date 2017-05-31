<div class="bg-danger errors">Произошли ошибки:
    <ul>
        <?php foreach ($data['errors'] as $key => $value): ?>
            <li><?php echo $value; ?></li>
        <?php endforeach; ?>
    </ul>
</div>

