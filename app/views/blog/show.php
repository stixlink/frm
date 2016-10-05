<?php
/**
 * @var app\models\Post $post
 */
$controller = $this->getController();
?>

<div class = "row">

    <div class = "blog-header">
        <h1 class = "blog-title"><?= $post->getTitle(); ?></h1>
    </div>

    <div class = "col-sm-8 blog-main">

        <div class = "blog-post">
            <p class = "blog-post-meta"><?= $post->getDate(); ?></p>
            <? if ($post->getUrlImage()): ?>
                <img src = "<?= $post->getUrlImage() ?>" width = "250" alt = "<?= $post->getTitle() ?>">
            <? endif; ?>
            <p><?= $post->getBody(); ?></p>
        </div>
    </div>
    <div class = "col-sm-3 col-sm-offset-1 blog-sidebar">
        <div class = "sidebar-module">
            <h4>Меню</h4>
            <ol class = "list-unstyled">
                <li><a data-pjax = "1" href = "<?= $controller->createUrl('/admin/update', ['id' => $post->id]) ?>">Редактировать</a>
                </li>
                <li><a data-pjax = "1" href = "<?= $controller->createUrl('/admin/create') ?>">Добавить</a></li>
                <li><a data-pjax = "1" href = "<?= $controller->createUrl('/admin/delete', ['id' => $post->id]) ?>">Удалить</a>
                </li>
            </ol>
        </div>
    </div><!-- /.blog-sidebar -->
</div>
