<?php
/**
 * Created by Stixlink.
 * E-mail: stixlink@gmail.com
 * Skype: stixlink
 * Date: 17.01.16
 * Time: 23:32
 */

namespace core;


use core\db\SDBQuery;

//TODO do methods of validate method
abstract class Model {

    protected $_attributes = array();
    private $_executor;
    private $_errors = array();

    abstract public function getTableName();

    abstract public function getPKName();

    public function __construct() {

        $this->_executor = new SDBQuery();
    }

    /**
     * @param string $className
     *
     * @return mixed
     */
    public static function instance($className = __CLASS__) {

        return new $className();
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value) {

        $this->_attributes[$name] = $value;
    }


    /**
     * @param $name
     *
     * @return null
     */
    public function __get($name) {

        return isset($this->_attributes[$name]) ? $this->_attributes[$name] : null;
    }

    /**
     * @return array
     */
    public function getErrors() {

        return $this->_errors;
    }

    /**
     * @param   String $name
     *
     * @return  null
     */
    public function getError($name) {

        return isset($this->_errors[$name]) ? $this->_errors[$name] : null;
    }

    /**
     * @param   String $name
     * @param   String $value
     */
    public function setError($name, $value) {

        $this->_errors[$name];
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes) {

        foreach ($attributes as $k => $v) {
            $this->{$k} = $attributes[$k];
        }
    }

    /**
     * @param null $names
     *
     * @return array
     */
    public function getAttributes($names = null) {

        $values = $this->_attributes;
        if (is_array($names)) {
            $values2 = [];
            foreach ($names as $name) {
                $values2[$name] = isset($values[$name]) ? $values[$name] : null;
            }

            return $values2;
        } else {
            return $values;
        }
    }

    /**
     * @param $pk
     *
     * @return null
     */
    public function findByPk($pk) {

        $pkName = $this->getPKName();

        $result = $this->_executor->query("SELECT * FROM {$this->getTableName()} WHERE {$pkName}=:{$pkName}", [":{$pkName}" => $pk]);
        if ($result) {
            $this->_attributes = $result;

            return $this;
        } else {
            return null;
        }
    }

    /**
     * @param array $condition ['limit'=>['offset'=>N,'rows'=>T], 'where'=>"...", 'order'=>"...", 'group'=>"..."]
     * @param array $params
     *
     * @return array|null
     */
    public function findAll(array $condition = [], array $params = []) {

        $query = "SELECT * FROM {$this->getTableName()} " . $this->generateQueryCondition($condition);

        $results = $this->_executor->queryAll($query, $params);
        if ($results && is_array($results)) {
            $resultArray = [];
            foreach ($results as $result) {
                $obj = $this::instance();
                $obj->setAttributes($result);
                $resultArray[] = $obj;
            }

            return $resultArray;
        } else {
            return null;
        }
    }


    /**
     * @param array $condition
     *
     * @return string
     */
    public function generateQueryCondition(array $condition) {

        $query = '';
        if (count($condition)) {
            $query .= isset($condition['where']) ? (" WHERE " . $condition['where']) : "";

            $query .= isset($condition['order']) ? (" ORDER BY " . $condition['order']) : "";
            $query .= isset($condition['group']) ? (" GROUP BY " . $condition['group']) : "";
            $query .=
                (isset($condition['limit']) && is_array($condition['limit'])) ?
                    (" LIMIT "
                     . (isset($condition['limit']['offset']) ? ((int)$condition['limit']['offset'] . " ") : "0 ")
                     . (isset($condition['limit']['rows']) ? (", " . (int)$condition['limit']['rows']) : "")) : "";
        }

        return $query;
    }

    /**
     * @param $query
     * @param $params
     *
     * @return array|null
     */
    public function findBySqlAll($query, $params) {

        return $this->_executor->queryAll($query, $params);
    }

    /**
     * @param $query
     * @param $params
     *
     * @return mixed|null
     */
    public function findBySql($query, $params) {

        return $this->_executor->query($query, $params);
    }

    /**
     * @param       $sql
     * @param array $params
     * @param bool  $isUpdate
     *
     * @return bool|mixed|null
     */
    public function execute($sql, $params = [], $isUpdate = false) {

        return $this->_executor->query($sql, $params, $isUpdate);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function __isset($key) {

        if (isset($this->$key)) {
            return true;
        }

        return isset($this->_attributes[$key]);
    }

    /**
     * @param $key
     */
    public function __unset($key) {

        unset($this->$key);
        unset($this->_attributes[$key]);
    }

    /**
     * @return bool
     */
    public function update() {

        $this->beforeUpdate();
//TODO added validation fields
        $fields = '';
        $params = [];
        if (count($this->getTableFields())) {
            foreach ($this->getTableFields() as $v) {
                if (isset($this->{$v})) {
                    if ($v != $this->getPKName()) {
                        if ($fields != "") {
                            $fields .= ", ";
                        }

                        $params[":{$v}"] = $this->{$v};
                        $fields .= "`{$v}`= :{$v}";
                    }
                }
            }
        }
//TODO Error during execution of a sql query UPDATE
        $id = $this->{$this->getPKName()};
        $result = $this->_executor->query("UPDATE `{$this->getTableName()}` SET {$fields} WHERE `{$this->getPKName()}`='{$id}'", $params, true, false);
        if ($result == 1) {
            $this->afterUpdate();
        }

        return $result == 1;
    }

    public function save() {

        $this->beforeInsert();
//TODO added validation fields
        $fields = '';
        $params = [];

        if (count($this->getTableFields())) {
            foreach ($this->getTableFields() as $v) {
                if (isset($this->{$v})) {
                    if ($v != $this->getPKName()) {
                        if ($fields != "") {
                            $fields .= ", ";
                        }

                        $params[":{$v}"] = $this->{$v};
                        $fields .= "`{$v}`";
                    }
                }
            }
        }
        $values = implode(', ', array_keys($params));
        $result = $this->_executor->query("INSERT INTO `{$this->getTableName()}` ({$fields}) VALUES ({$values})", $params, true, true);
        if ($result) {
            $this->{$this->getPKName()} = $result;
            $this->afterInsert();
        }

        return $result == 1;
    }

    public function delete() {

        $result = false;
        if ($this->{$this->getPKName()}) {
            $result = $this->_executor->query("DELETE FROM `{$this->getTableName()}` WHERE `{$this->getPKName()}`= :{$this->getPKName()};",
                [':' . $this->getPKName() => $this->{$this->getPKName()}], true, false);
            if ($result) {

                $this->afterDelete();
            }
        }

        return $result == 1;
    }

//TODO receive fields model table through PDO
    abstract public function getTableFields();

    public function beforeUpdate() {

    }

    public function afterUpdate() {

    }


    public function beforeInsert() {

    }

    public function afterInsert() {

    }

    public function afterDelete() {

    }


}
