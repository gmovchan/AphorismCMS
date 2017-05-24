<div class="inner cover">
    <?php foreach ($data['offers'] as $offer): ?>
        <div class="panel panel-default panel-quote">
            <div class="panel-body">
                <p><b>Текст цитаты:</b> <?php echo $this->html($offer['quote_text']); ?></p>
                <p><b>Автор:</b> <?php echo $this->html($offer['author_quote']); ?></p>
                <p><b>Предложил:</b> <?php echo $this->html($offer['author_offer']); ?></p>
                <p><b>Источник:</b> <?php echo $this->html($offer['source_quote']); ?></p>
                <p><b>Комментарий:</b> <?php echo $this->html($offer['comment']); ?></p>
                <p><b>Время создания:</b> <?php echo $offer['time_add_offer']; ?></p>
            </div>
            <div class="panel-footer">
                <a href="/admin/offer/editoffer?offer_id=<?php echo $offer['id']; ?>">Открыть</a>
                <span> / </span>
                <a href="/admin/offer/deloffer?offer_id=<?php echo $offer['id']; ?>">Удалить</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>