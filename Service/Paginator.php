<?php

namespace P\AdminBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Paginator
{
    private $container;
    private $templating;
    private $requestStack;
    private $request;

    public function __construct(ContainerInterface $container, $templating, RequestStack $requestStack)
    {
        $this->container = $container;
        $this->templating = $templating;
        $this->requestStack = $requestStack;
        $this->request = $this->requestStack->getCurrentRequest();
    }

    public function getOffset($page, $limit)
    {
        return ($page - 1) * $limit;
    }

    public function getPagination($query, $page, $limit=20, $count=null)
    {
        if($count == null) {
            $count = $this->getCount(clone $query);
        }
        $pageCount = ceil($count / $limit);
        return array(
            'offset' => $this->getOffset($page, $limit),
            'count' => intval($count),
            'page' => $page,
            'pageCount' => $pageCount
        );
    }

    public function query($query, $page, $limit=20, $count=null, $cacheId=null, $second=60)
    {
        $limit = $limit ?: 20;
        $page = $page ?: 1;
        $offset = $this->getOffset($page, $limit);

        $pagination = $this->getPagination($query, $page, $limit);
        $results = $this->getResults($query, $limit, $offset, $cacheId);

        return array(
            $results,
            $pagination
        );
    }

    public function renderView($pagination, $route=null, $routeParams=null, $template=null)
    {
        if($route == null) {
            $route = $this->request->get('_route');
        }
        if(empty($template)) {
            $template = $this->container->getParameter('p.paginator.template');
        }
        if(empty($routeParams)) {
            $routeParams = array();
        }
        return $this->templating->render($template, array(
            'pagination' => $pagination,
            'route' => $route,
            'routeParams' => $routeParams
        ));
    }

    public function getCount($query)
    {
        return $query
            ->select($query->expr()->count($query->getRootAlias()))
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getResults($query, $limit, $offset, $cacheId=null, $second=60)
    {
        $_query = $query
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ;
        if($cacheId != null) {
            $_query->useResultCache(true, $second, $cacheId);
        }
        return $_query->getResult();
    }
}

