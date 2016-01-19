<?php
/**
 * @var app\models\News $news
 */
?>
<div class = "row">
    <div class = "news-list-link"><a href = "/news">Вернуться в список новостей</a></div>
    <div class = "news-block">
        <div class = "news-date"><?= $news->getDate(); ?></div>
        <div class = "news-title"><?= $news->getTitle(); ?></div>
        <div class = "news-body"><?= $news->getBody(); ?></div>
    </div>

</div>
