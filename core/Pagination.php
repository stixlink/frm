<?php
/**
 * Created by Stixlink.
 * E-mail: stixlink@gmail.com
 * Skype: stixlink
 * Date: 19.01.16
 * Time: 17:40
 */
/**
 * @property core\Model $_model
 */
namespace core;

use core\Model;

class Pagination
{

    public $pageSize = 2;
    public $offset = 0;
    public $limit;
    private $_model;
    private $_condition;
    private $_params;


    public function __construct(Model $model, array $condition = [], array $params = [], $pageSize = null)
    {
        if (!$pageSize) {
            $this->pageSize = (int)$pageSize;
        }
        $this->_model = $model;
        $this->_condition = $condition;
        $this->_params = $params;
    }

    public function getPageCount($condition = [], $params = [])
    {
        $queryCondition = $this->_model->generateQueryCondition($condition);
        $itemCount = $this->_model->findBySql("SELECT COUNT(*) as `count` FROM {$this->_model->getTableName()} " . $queryCondition, $params);
        if (isset($itemCount['count'])) {
            return (int)(($itemCount['count'] + $this->pageSize - 1) / $this->pageSize);
        }

        return 0;
    }

    public function getPage($pageCount)
    {
        $page = empty($_GET['page']) ? 1 : $_GET['page'];
        if ($page > $pageCount) {
            $page = $pageCount;
        }
        if ($page < 1) {
            $page = 1;
        }

        return $page;
    }

    public function generate()
    {
        $pageCount = $this->getPageCount($this->_condition, $this->_params);
        $pageNum = $this->getPage($pageCount);
        $result = ['countPage' =>$pageCount ,
                   'limit' => ['offset' => ($pageNum - 1) * $this->pageSize,
                               'rows' => $this->pageSize],
                   'pageNum' => $pageNum];

        return $result;
    }
}
