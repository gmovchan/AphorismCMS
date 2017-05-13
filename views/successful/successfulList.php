<div class="bg-success">Успешно выполнено:
    <ul>
        <?php if (!is_null($data['successful'])):
            foreach ($data['successful'] as $key => $value): ?>
                <li><?php echo $value; ?></li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

