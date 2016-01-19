<?php
/**
 * Created by Stixlink.
 * E-mail: stixlink@gmail.com
 * Skype: stixlink
 * Date: 17.01.16
 * Time: 23:30
 */

/**
 * @property \core\View $view
 */
namespace core;


use core\View;
use core\exception\HttpException;

class Controller {

    public $defaultAction = "index";
    private $_action;
    private $_id;
    private $_prefixAction = "action";
    protected $view;


    public function __construct($id, $action = null) {

        $this->_id = $id;
        $this->view = new View($this);
        $this->defaultAction = ucfirst($this->defaultAction);
        $this->_action = $this->_prefixAction . ucfirst($action ? $action : $this->defaultAction);
    }

    public function run($params = null) {

        $this->_action = (isset($params['action']) && $params['action']) ? $params['action'] : $this->_action;

        if (is_callable(array($this, $this->_action))) {
            $this->{$this->_action}($params);
            exit();
        }
        throw new HttpException("Not exist action \"{$this->_action}\" in controller \"" . get_class($this) . "\"!", 404);


    }

    public function getId() {

        return $this->_id;
    }
}
