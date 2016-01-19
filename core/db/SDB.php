<?php

/**
 * Created by stixlink.
 * E-mail: stixlink@gmail.com
 * Skype: stixlink
 * Date: 30.12.14
 * Time: 14:48
 */
namespace core\db;


use core\exception\DBException;

/**
 * Class SDB
 *
 * @property PDO   $pdo
 * @property Array $serverConfig
 */
class SDB {

    private $pdo;
    private $_active = false;
    private $serverConfig;
    private $username = '';
    private $password = '';
    private $dsn = '';
    private $host = 'localhost';
    private $charset = 'utf8';
    private $db = '';
    private $opt;

    public function __construct() {

        $dbConfigPath = BASE_PATH . DS . APP_DIR . DS . "config" . DS . "db.php";
        if (!file_exists($dbConfigPath)) {
            throw new DBException("Not exist database config file \"$dbConfigPath\"");
        }
        $this->serverConfig = include(BASE_PATH . DS . APP_DIR . DS . "config" . DS . "db.php");
        if (is_array($this->serverConfig)) {
            $reflection = new \ReflectionClass($this);
            $properties = $reflection->getDefaultProperties();
            foreach ($this->serverConfig as $key => $value) {
                if (isset($properties[$key]) && $properties[$key] !== null) {
                    $this->$key = $value;
                }
            }
        }
        if (!$this->dsn) {
            $this->dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        }

        $this->opt = array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        );

    }

    private function initConnection() {

        if ($this->charset !== null) {
            $driver = strtolower($this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME));
            if (in_array($driver, array('pgsql', 'mysql', 'mysqli'))) {
                $this->pdo->exec('SET NAMES ' . $this->pdo->quote($this->charset));
            }
        }
    }

    public function open() {

        try {
            $this->pdo = new \PDO($this->dsn, $this->username, $this->password, $this->opt);
            $this->initConnection();
            $this->_active = true;
        } catch (\PDOException $e) {
            throw new DBException($e->getMessage());
        }
    }

    public function getInstance() {

        if ($this->pdo) {
            return $this->pdo;
        } else {
            $this->open();

            return $this->pdo;
        }
    }

    public function __sleep() {

        $this->close();

        return array_keys(get_object_vars($this));

    }

    protected function close() {

        $this->pdo = null;
        $this->_active = false;
    }
}
