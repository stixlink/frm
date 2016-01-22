<?php
/**
 * Created by Stixlink.
 * E-mail: stixlink@gmail.com
 * Skype: stixlink
 * Date: 17.01.16
 * Time: 23:32
 */

namespace core;

class View
{
    public $titlePage;

    private $_layouts = "main";
    private $_controller;
    private $_defaultDir = "views";
    private $_layoutsDir = "layouts";
    private $_extensionsFile = "php";

    public function __construct($controller)
    {
        $this->_controller = $controller;
    }

    /**
     *
     * @return mixed
     */
    public function getController()
    {
        return $this->_controller;
    }

    public function render($view, $params, $inLayout = true, $return = false)
    {
        $path = $this->getViewPath($view);
        $content = $this->getViewContent($path, $params);
        if ($inLayout) {
            $content = $this->getViewContent($this->getLayoutsPath(),
                [
                    'content' => $content,
                    'titlePage' => $this->titlePage
                ]
            );
        }
        if ($return) {
            return $content;
        } else {
            echo $content;
            exit();
        }
    }

    /**
     * @param $view
     *
     * @return string
     * @throws \Exception
     */
    public function getViewPath($view)
    {
        $path = BASE_PATH . DS . APP_DIR . DS . $this->_defaultDir . DS . ($this->_controller->getId() . DS . $view . "." . $this->_extensionsFile);
        if (file_exists($path)) {
            return $path;
        }
        throw new  \Exception('Not exist view file "' . $path . '"');
    }

    /**
     * @param      $pathViewFile
     * @param null $data
     * @param bool $return
     *
     * @return string
     */
    protected function getViewContent($pathViewFile, $data = null, $return = true)
    {
        if (is_array($data)) {
            extract($data, EXTR_PREFIX_SAME, 'data');
        }
        if ($return) {
            ob_start();
            ob_implicit_flush(false);
            require($pathViewFile);

            return ob_get_clean();
        } else {
            require($pathViewFile);
        }
    }

    public function getLayoutsPath()
    {
        $path = BASE_PATH . DS . APP_DIR . DS . $this->_defaultDir . DS . $this->_layoutsDir . DS . $this->getLayoutsName() . "." . $this->_extensionsFile;
        if (file_exists($path)) {
            return $path;
        }
        throw new  \Exception('Not exist layout file "' . $path . '"');
    }

    /**
     * @return string
     */
    public function getLayoutsName()
    {
        return $this->_layouts;
    }

    /**
     * @param $value
     */
    public function setLayoutsName($value)
    {
        $this->_layouts = $value;
    }
}
