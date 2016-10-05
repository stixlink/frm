<?php
/**
 * Created by Stixlink.
 * E-mail: stixlink@gmail.com
 * Skype: stixlink
 * Date: 04.10.16
 * Time: 13:55
 *
 *
 * @property core\View $view
 */

namespace app\controllers;


use core;
use core\exception\HttpException;
use core\Request;
use app\models\Post;

class AdminController extends core\Controller {


    public function actionDelete($id) {

        $post = $this->getPost($id);
        if ($post) {
            $post->delete();
            $this->redirect($this->createUrl('/blog/index'));
            exit();
        }
        $this->redirect($this->createUrl($_SERVER['referrer']));

        exit();
    }

    public function actionUpdate($id) {

        $this->view->titlePage = "Редактирование поста";
        /**
         * @var Post $post
         */
        $post = $this->getPost($id);
        if (isset($_POST['Post'])) {
            $post->setAttributes($_POST['Post']);
            $post->active = isset($_POST['Post']['active']) ? 1 : 0;
            $post->body = isset($_POST['Post']['body']) ? trim($_POST['Post']['body']) : $post->body;
            $result = $post->update();

            $this->redirect($this->createUrl('/blog/show', ['id' => $id]));
            exit();

        }

        if (Request::isAjax()) {
            echo $this->view->render('update', ['post' => $post], false, false);
            exit();
        }
        $this->view->render('update', ['post' => $post]);
    }

    public function actionCreate() {

        $this->view->titlePage = "Редактирование поста";
        /**
         * @var Post $post
         */
        $post = new Post();
        if (isset($_POST['Post'])) {
            $post->setAttributes($_POST['Post']);
            $post->active = isset($_POST['Post']['active']) ? 1 : 0;
            $post->body = isset($_POST['Post']['body']) ? trim($_POST['Post']['body']) : $post->body;
            $result = $post->save();
            if ($result) {
                $this->redirect($this->createUrl('/blog/show', ['id' => $result]));
                exit();
            }
        }

        if (Request::isAjax()) {
            echo $this->view->render('create', ['post' => $post], false, false);
            exit();
        }
        $this->view->render('create', ['post' => $post]);
    }

    /**
     * @param $id
     *
     * @return Post
     * @throws HttpException
     */
    public function getPost($id) {

        $post = Post::instance()->findByPk((int)$id);
        if ($post == null) {
            throw new HttpException(404, "Post not found");
        }

        return $post;
    }
}
