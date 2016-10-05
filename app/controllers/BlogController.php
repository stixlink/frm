<?php
/**
 * Created by Stixlink.
 * E-mail: stixlink@gmail.com
 * Skype: stixlink
 * Date: 19.01.16
 * Time: 0:25
 *
 * @property core\View $view
 */
namespace app\controllers;


use core;
use core\exception\HttpException;
use core\Request;
use app\models\Post;

class BlogController extends core\Controller {

    public function actionIndex() {

        $this->view->titlePage = "Список постов";
        $criteria = ['where' => 'active=:active',
                     'order' => 'date_update DESC',
        ];
        $params = [':active' => 1];
        $pageSize = 2;
        $pagination = new core\Pagination(Post::instance(), $criteria, $params, $pageSize);

        $criteria['limit'] = $pagination->getLimit();

        $posts = Post::instance()->findAll($criteria, $params);

        $params = ['posts' => $posts,
                   'pagination' => $pagination->getPaginate()
        ];

        if (Request::isAjax()) {
            echo $this->view->render("index", $params, false, false);
            exit();
        } else {
            $this->view->render("index", $params);
        }
    }

    public function actionShow($id) {

        $post = Post::instance()->findByPk((int)$id);
        if ($post && isset($post->title)) {
            $this->view->titlePage = "Блог - " . $post->title;
        } else {
            throw new HttpException(404, "Not found page.");
        }
        if (Request::isAjax()) {
            echo $this->view->render("show", ['post' => $post], false, false);
            exit();
        }
        $this->view->render("show", ['post' => $post]);
    }

    public function actionError() {

    }
}
