<?php

namespace P\AdminBundle\Component\Dashboard;

abstract class AbstractWidget
{
    private $templating;

    public $view;
    public $parameters = array();

    public $enabled;
    public $sort;

    public $widgetId;
    public $widgetName;
    public $uniqId;

    public function __construct()
    {
        $this->uniqid = md5(uniqid(self::class) . rand(0, 100));
    }

    public function setTemplating($templating)
    {
        $this->templating = $templating;
    }

    public function getTemplating()
    {
        return $this->templating;
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function render()
    {
        return $this->templating->render($this->view, array(
            'widget' => $this
        ));
    }
}
