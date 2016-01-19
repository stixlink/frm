<?php
/**
 * @var app\models\News[] $news
 */
?>
<div class = "row">
    <h1>Новости</h1>
    <?php if (is_array($news)): ?>
        <div id = "news-list">
            <?php foreach ($news as $n): ?>
                <div class = "article">
                    <div class = "news-title"><a href = "/news/show/<?= $n->id ?>">
                            <span class = "date"><?= $n->getDate(); ?></span> <?= $n->getTitle(); ?>

                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        if ($pagination['countPage'] > 1) { ?>
            <div class = "pagination-block">
                <?php for ($i = 1; $pagination['countPage'] >= $i; $i++) { ?>
                    <a href = "/news?page=<?= $i ?>" data-page = "<?= $i ?>"
                       class = "<?= $pagination['pageNum'] == $i ? "active" : ''; ?> pagination-item"><?php echo $i ?></a>
                <?php } ?>
            </div>
        <?php } ?>
    <?php endif; ?>
</div>
