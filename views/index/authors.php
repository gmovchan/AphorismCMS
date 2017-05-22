<div class="inner cover">
    <ul class="list-group authors">
        <?php foreach ($data['authors'] as $author): ?>   
            <li class="list-group-item">
                <span class="badge"><?php echo $author['countQuotes']; ?></span>
                <a href="/quotes?author_id=<?php echo $author['id']; ?>"><?php echo $author['name']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>