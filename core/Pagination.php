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

class Pagination {

    public $pageSize = 2;
    public $offset = 0;
    public $limit;
    public $pageVar = 'page';
    private $_model;
    private $_condition;
    private $_params;


    public function __construct(Model $model, array $condition = [], array $params = [], $pageSize = null) {

        if (!$pageSize) {
            $this->pageSize = (int)$pageSize;
        }
        $this->_model = $model;
        $this->_condition = $condition;
        $this->_params = $params;
    }

    public function getPageCount($condition = [], $params = []) {

        $queryCondition = $this->_model->generateQueryCondition($condition);
        $itemCount = $this->_model->findBySql("SELECT COUNT(*) as `count` FROM {$this->_model->getTableName()} " . $queryCondition, $params);
        if (isset($itemCount['count'])) {
            return (int)(($itemCount['count'] + $this->pageSize - 1) / $this->pageSize);
        }

        return 0;
    }

    public function getPage($pageCount) {

        $page = empty($_GET[$this->pageVar]) ? 1 : $_GET[$this->pageVar];
        if ($page > $pageCount) {
            $page = $pageCount;
        }
        if ($page < 1) {
            $page = 1;
        }

        return $page;
    }

    public function getLimit() {

        $page = $this->getPaginate();

        $limitParams = ['offset' => ($page->getPageNum() - 1) * $page->getPageSize(),
                        'rows' => $page->getPageSize()];

        return $limitParams;
    }

    /**
     * @return Paginate
     */
    public function getPaginate() {

        $pageCount = $this->getPageCount($this->_condition, $this->_params);
        $pageNum = $this->getPage($pageCount);
        $paging = new Paginate($pageCount, $pageNum, $this->pageSize);

        return $paging;
    }
}
