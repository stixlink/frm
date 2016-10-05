<?php
/**
 * Created by Stixlink.
 * E-mail: stixlink@gmail.com
 * Skype: stixlink
 * Date: 04.10.16
 * Time: 10:41
 */

namespace core;


/**
 * Class Paginate
 *
 * @package core
 *
 * @property  int $_pageSize
 * @property  int $_pageNum
 * @property  int $_pageCount
 * @property  int $_prevPage
 * @property  int $_nextPage
 */
class Paginate {
    private $_pageSize = 10;
    private $_pageNum = 1;
    private $_pageCount = 0;
    private $_prevPage;
    private $_nextPage;

    /**
     * Paginate constructor.
     *
     * @param $pageCount
     * @param $pageNum
     * @param $pageSize
     */
    public function __construct($pageCount, $pageNum, $pageSize) {

        if ($pageCount) {
            $this->_pageCount = (int)$pageCount;
        }
        if ($pageNum) {
            $this->_pageNum = (int)$pageNum;
        }
        if ($pageSize) {
            $this->_pageSize = (int)$pageSize;
        }
    }

    /**
     * @return int
     */
    public function getPageNum() {

        return $this->_pageNum;
    }

    /**
     * @return int
     */
    public function getPageSize() {

        return $this->_pageSize;
    }

    /**
     * @return int
     */
    public function getCountPage() {

        return $this->_pageCount;
    }

    /**
     * @return int|null
     */
    public function getPrevPage() {

        if ( $this->_pageNum > 1) {
            return $this->_pageNum - 1;
        }

        return null;
    }

    /**
     * @return int|null
     */
    public function getNextPage() {

        if ($this->_pageCount > $this->_pageNum && $this->_pageCount > $this->_pageNum) {
            return $this->_pageNum + 1;
        }

        return null;
    }
}
