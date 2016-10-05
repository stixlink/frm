<?php
/**
 * Created by Stixlink.
 * E-mail: stixlink@gmail.com
 * Skype: stixlink
 * Date: 04.10.16
 * Time: 14:26
 */
$controller = $this->getController();
?>

<div class = "row">

    <div class = "blog-header">
        <h1 class = "blog-title">Создание поста</h1>
    </div>
    <div class = "col-sm-8 blog-main">
        <form enctype = "multipart/form-data"
              action = "<?= $controller->createUrl('/admin/update', ['id' => $post->id]) ?>" method = "post">
            <div class = "form-group">
                <label for = "Post[title]">Заголовок</label>
                <p>
                    <input type = "text" name = "Post[title]" id = "Post_title" class = "form-control"
                           value = "<?= $post->title ? $post->title : "" ?>" />
                </p>
            </div>
            <div class = "form-group">
                <label for = "Post[body]">Текст</label>
                <p>
                    <textarea rows = "10" cols = "53" name = "Post[body]"
                              id = "Post_body"><?= $post->body ? $post->body : "" ?></textarea>
                </p>
            </div>
            <div class = "form-group">
                <label for = "Post[active]">Активность</label>
                <p>
                    <input type = "checkbox" name = "Post[active]" id = "Post_active"
                    <?= $post->active ? " checked='checked'" : "" ?>" />
                </p>
            </div>
            <div class = "form-group">
                <label for = "Post[image]">Изображение к посту (jpg, jpeg)</label>
                <p>
                    <input type = "file" name = "image" />
                </p>
            </div>
            <input data-pjax = "1" type = "submit" value = "Сохранить">
        </form>

    </div>
    <div class = "col-sm-3 col-sm-offset-1 blog-sidebar">
        <div class = "sidebar-module">
            <h4>Меню</h4>
            <ol class = "list-unstyled">
                <li>
                    <a data-pjax = "1" href = "<?= $controller->createUrl('/blog/index') ?>">Список постов</a>
                </li>
            </ol>
        </div>
    </div><!-- /.blog-sidebar -->
</div>
