<?php

/**
 * Created by stixlink.
 * E-mail: stixlink@gmail.com
 * Skype: stixlink
 * Date: 31.12.14
 * Time: 3:36
 */
namespace core\db;


class SDBQuery {

    /**
     * @param       $query
     * @param array $params
     *
     * @return array|null
     */
    public function queryAll($query, Array $params = array()) {

        $stm = $this->execute($this->createCommand($query), $params);
        if ($stm == null) {
            return null;
        }

        return $stm->fetchAll();
    }

    /**
     * @param       $query
     * @param array $params
     *
     * @return mixed|null
     */
    public function query($query, Array $params = array()) {

        $stm = $this->execute($this->createCommand($query), $params);
        if ($stm == null) {
            return null;
        }

        return $stm->fetch();
    }

    /**
     * @param       $query
     * @param array $params
     *
     * @return null|string
     */
    public function queryScalar($query, Array $params = array()) {


        $stm = $this->execute($this->createCommand($query), $params);
        if ($stm == null) {
            return null;
        }

        return $stm->fetchColumn();
    }

    /**
     * @param $query
     *
     * @return PDOStatement
     */
    public function createCommand($query) {

        $db = $this->getDBConnect();

        return $db->prepare($query);
    }

    /**
     * @param PDOStatement $statement
     * @param array        $params
     *
     * @return null|PDOStatement
     */
    public function execute(\PDOStatement $statement, array $params = null) {

        if (!$statement->execute($params)) {
            return null;
        }

        return $statement;
    }

    protected function getDBConnect() {

        $db = new SDB();

        return $db->getInstance();
    }
}
