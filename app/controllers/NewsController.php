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
use app\models\News;

class NewsController extends core\Controller
{

    public function actionIndex()
    {
        $this->view->titlePage = "Новости";
        $criteria = ['where' => 'active=:active',
                     'order' => 'date_create DESC',
        ];
        $params = [':active' => 1];
        $pageSize = 2;
        $pagination = new core\Pagination(News::instance(), $criteria, $params, $pageSize);
        $paginationData = $pagination->generate();
        $criteria['limit'] = $paginationData['limit'];

        $news = News::instance()->findAll($criteria, $params);

        if (Request::isAjax()) {
            $returnData = ['content' => $this->view->render("index", ['news' => $news], false, true),
                           'status' => true];
            $returnJsonData = json_encode($returnData);
            echo $returnJsonData;
            exit();
        } else {
            $this->view->render("index", ['news' => $news, 'pagination' => $paginationData]);
        }
    }

    public function actionShow($id)
    {
        $news = News::instance()->findByPk((int)$id);
        if ($news && isset($news->title)) {
            $this->view->titlePage = "Новости - " . $news->title;
        } else {
            throw new HttpException(404, "Not found page.");
        }

        $this->view->render("show", ['news' => $news]);
    }
}
