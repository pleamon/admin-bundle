<?php

namespace P\AdminBundle\Table;

use P\AdminBundle\Table\Field;

class Table
{
    private $container;
    private $query;
    private $page;
    private $route;
    private $routeParams;
    private $fields = array();
    private $datas;
    private $loaded;

    private $paginator;
    private $templating;

    private $tools;

    public function __construct($container, $query, $page, $route, $routeParams=array())
    {
        $this->container = $container;
        $this->query = $query;
        $this->page = $page;
        $this->route = $route;
        $this->routeParams = $routeParams;

        $this->paginator = $container->get('p.paginator');
        $this->templating = $container->get('templating');
    }

    public function addField($field)
    {
        array_push($this->fields, $field);
    }

    public function add($name, $options=array())
    {
        $field = new Field($name, $options);
        $this->addField($field);

        return $this;
    }

    public function load()
    {
        if($this->loaded) {
            return;
        }
        list($rows, $pagination) = $this->paginator->query($this->query, $this->page);

        $this->datas = array();

        foreach($rows as $row) {
            $data = array();
            foreach($this->fields as $field) {
                $name = $field->getName();
                $value = $field->getValue($row[$name]);
                array_push($data, $value);
            }
            array_push($this->datas, $data);
        }

        $this->tools = $this->paginator->renderView($pagination, $this->route, $this->routeParams);
        $this->loaded = true;
    }

    public function reload()
    {
        $this->loaded = false;
        $this->load();
    }

    public function render()
    {
        $this->load();
        return $this->templating->render('PAdminBundle:form:__list.html.twig', array(
            'fields' => $this->fields,
            'datas' => $this->datas,
            'tools' => $this->tools
        ));
    }

    public function getData()
    {
        return $this->datas;
    }
}
