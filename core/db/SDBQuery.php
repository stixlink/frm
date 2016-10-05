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

    private $_connect;

    /**
     * @param       $query
     * @param array $params
     *
     * @return array|null
     */
    public function queryAll($query, array $params = array()) {

        $stm = $this->execute($this->createCommand($query), $params);
        if ($stm == null) {
            return null;
        }

        return $stm->fetchAll();
    }

    /**
     * @param       $query
     * @param array $params
     * @param bool  $isUpdate
     * @param bool  $isCreate
     *
     * @return mixed|null|string
     */
    public function query($query, array $params = array(), $isUpdate = false, $isCreate = false) {

        $stm = $this->createCommand($query);
        if (!$isUpdate) {
            $stm = $this->execute($stm, $params);

            if ($stm == null) {
                return null;
            }
        }

        if ($isUpdate) {
            if (count($params)) {
                foreach ($params as $k => $v) {
                    $p = is_int($v) ? \PDO::PARAM_INT : \PDO::PARAM_STR;
                    $stm->bindValue($k, $v, $p);
                }
            }

            if ($res = $stm->execute()) {
                if ($isCreate) {
                    $res = $this->getDBConnect()->lastInsertId();
                }

                return $res;
            } else {
                return null;
            }
        }


        return $stm->fetch();
    }

    /**
     * @param       $query
     * @param array $params
     *
     * @return null|string
     */
    public function queryScalar($query, array $params = array()) {

        $stm = $this->execute($this->createCommand($query), $params);
        if ($stm == null) {
            return null;
        }

        return $stm->fetchColumn();
    }

    /**
     * @param $query
     *
     * @return \PDOStatement
     */
    public function createCommand($query) {

        $db = $this->getDBConnect();

        return $db->prepare($query);
    }

    /**
     * @param \PDOStatement $statement
     * @param array         $params
     *
     * @return null|\PDOStatement
     */
    public function execute(\PDOStatement $statement, array $params = null) {

        if (count($params)) {
            foreach ($params as $k => $v) {
                $statement->bindParam($k, $v);
            }
        }
        if (!$statement->execute()) {
            return null;
        }

        return $statement;
    }

    protected function getDBConnect() {

        if ($this->_connect) {
            return $this->_connect;
        }
        $db = new SDB();
        $this->_connect = $db->getInstance();

        return $this->_connect;
    }
}
