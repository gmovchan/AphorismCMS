<!-- Скрипт для кнопок -->
<script src="<?php echo $data['publicDir']; ?>/js/quotes.js"></script>
<div itemscope itemtype="http://schema.org/CreativeWork">
    <?php foreach ($data['quotes'] as $qoute): ?>
        <div class="panel panel-default panel-quote" id="quote<?php echo $qoute['quote_id']; ?>">
            <div class="panel-body">
                <blockquote class="text-left">
                    <p>
                        <span itemprop="description">
                            <?php if ($qoute['lengthExceeded'] === true): ?>
                                <span class="startText"><?php echo $this->html($qoute['startText']); ?></span><a data-id="<?php echo $qoute['quote_id']; ?>" class="get-full-text" onclick="retirn false"> ...развернуть полностью </a><span class="endText" id="end-text-id<?php echo $qoute['quote_id']; ?>"><?php echo $this->html($qoute['endText']); ?></span>
                            <?php else: ?>
                                <?php echo $this->html($qoute['text']); ?>
                            <?php endif; ?>
                        </span>
                    </p>
                    <footer>
                        <span itemprop="author"><a href="/quotes?author_id=<?php echo $qoute['author_id']; ?>"><cite title="<?php echo $this->html($qoute['author']); ?>"><?php echo $this->html($qoute['author']); ?></cite></a></span>
                    </footer>
                </blockquote>
                <?php
                // TODO: вставить кнопки для репоста
                // FIXME: если вставлять кнопки, то страница не загружается до конца. Возможно из-за их большого количества. Надо попробовать сделать постраничный вывод для списка всех цитат.
                ?>
            </div>
            <div class="panel-footer">
                <a href="/quotes#quote<?php echo $qoute['quote_id']; ?>">id<?php echo $qoute['quote_id']; ?></a>
                <span> / </span>
                <span itemprop="discussionUrl" href="http://<?php echo $data['hostLink']; ?>/quote/comments?quote_id=<?php echo $qoute['quote_id']; ?>"><a href="http://<?php echo $data['hostLink']; ?>/quote/comments?quote_id=<?php echo $qoute['quote_id']; ?>">Комментировать (<span itemprop="commentCount"><?php echo $qoute['amountComments']; ?></span>)</a></span>
            </div>
        </div>
    <?php endforeach; ?>
</div>

