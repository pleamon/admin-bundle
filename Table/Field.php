<?php

namespace P\AdminBundle\Table;

class Field
{
    private $label;
    private $name;
    private $_getValue;
    private $default = null;

    public function __construct($name, $options=array())
    {
        $this->label = $this->name = $name;

        foreach($options as $name => $option) {
            $this->setOption($name, $option);
        }
    }

    public function setOption($name, $option)
    {
        switch($name) {
        case 'label': $this->label = $option; break;
        case 'get': $this->_getValue = $option; break;
        case 'default': $this->default = $option; break;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setGetValue($func)
    {
        $this->_getValue = $func;
    }

    public function getValue($value)
    {
        $v = $value;
        if(!empty($this->_getValue)) {
            $v = call_user_func($this->_getValue, $value);
        }
        if($v === null && $this->default !== null) {
            $v = $this->default;
        }
        return $v;
    }
}
