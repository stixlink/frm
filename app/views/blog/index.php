<?php
/**
 * @var app\models\Post[] $posts
 * @var \core\Paginate    $pagination
 */
$controller = $this->getController();
?>
<div class = "blog-header">
    <h1 class = "blog-title">Список постов</h1>
</div>

<div class = "row">

    <div class = "col-sm-8 blog-main">
        <?php if (is_array($posts)): ?>
            <?php foreach ($posts as $n): ?>
                <div class = "blog-post">
                    <h2 class = "blog-post-title">
                        <a data-pjax = "1"
                           href = "<?= $controller->createUrl('/blog/show', ['id' => $n->id]) ?>"><?= $n->getTitle(); ?></a>
                    </h2>
                    <p class = "blog-post-meta"><?= $n->getDate(); ?>
                        <a data-pjax = "1"
                           href = "<?= $controller->createUrl('/admin/delete', ['id' => $n->id]) ?>"
                           data-id = "<?= $n->id ?>">
                            <span class = "glyphicon glyphicon-remove"
                                  title = "Удалить пост"
                                  aria-hidden = "true"></span>
                        </a>
                        <a data-pjax = "1" href = "<?= $controller->createUrl('/admin/update', ['id' => $n->id]) ?>">
                            <span class = "glyphicon glyphicon-pencil" title = "Редактировать пост"
                                  aria-hidden = "true"></span>
                        </a>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($pagination->getCountPage() > 1): ?>
            <nav>
                <ul class = "pager">
                    <? if ($pagination->getPrevPage() !== null): ?>
                        <li>
                            <a data-pjax = "1"
                               href = "<?= $controller->createUrl('/blog/index', ['page' => $pagination->getPrevPage()]) ?>"><<</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $pagination->getCountPage() >= $i; $i++): ?>
                        <li class = "<?= $pagination->getPageNum() == $i ? "active" : '' ?>">
                            <a data-pjax = "1"
                               href = "<?= $controller->createUrl('/blog/index', ['page' => $i]) ?>"><?php echo $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <? if ($pagination->getNextPage() !== null): ?>
                        <li>
                            <a data-pjax = "1"
                               href = "<?= $controller->createUrl('/blog/index', ['page' => $pagination->getNextPage()]) ?>"
                               class = "pagination-item">>></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>


    </div><!-- /.blog-main -->
    <div class = "col-sm-3 col-sm-offset-1 blog-sidebar">
        <div class = "sidebar-module">
            <h4>Меню</h4>
            <ol class = "list-unstyled">
                <li><a data-pjax = "1" href = "<?= $controller->createUrl('/admin/create') ?>">Добавить</a></li>
            </ol>
        </div>
    </div>
</div><!-- /.row -->
