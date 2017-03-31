<?php

namespace P\AdminBundle\Component\Core;

use P\AdminBundle\Comopnent\Core\Config\Info;

class Config
{
    public const CACHE_REGION = 'p.admin.core';
    private $container;
    private $em;

    private $config = array();

    public function __construct($container, $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function register($service, $serviceName, $attributes)
    {
        $configs = $service->getConfigs();
        foreach($configs as $idx => $config) {
            $group = $config->getGroup();
            $key = $config->getKey();
            if(!array_key_exists($group, $this->config)) {
                $this->config[$group] = array();
            }
            if(!array_key_exists($key, $this->config[$group])) {
                $this->config[$group][$key] = array();
            }
            $_config = $this->load($group, $key);
            $config->setSort($idx);
            $this->config[$group][$key] = $_config ? $_config : $config;
        }
    }

    public function getGroups()
    {
        return $this->config;
    }

    public function getConfigs()
    {
        return $this->config;
    }

    public function set($group, $key, $value)
    {
        $config = $this->get($group, $key);
        $config->setValue($value);
    }

    public function save()
    {
        foreach($this->config as $group) {
            foreach($group as $groupName => $config) {
                $this->em->persist($config);
            }
        }
        $this->em->flush();
        $this->clearCache();
    }

    public function clearCache()
    {
        $this->container->get('doctrine_cache.providers.result_cache_driver')->delete(self::CACHE_REGION);
    }

    public function get($group, $key = null)
    {
        if(!array_key_exists($group, $this->config)) {
            throw new \Exception(sprintf("not found group [ %s ]", $group));
        }

        if(empty($key)) {
            return $this->config[$group];
        }

        if(!array_key_exists($key, $this->config[$group])) {
            throw new \Exception(sprintf("not found [ %s ] from [ %s ]", $key, $group));
        }
        return $this->config[$group][$key];
    }

    public function load($group, $key = null)
    {
        if($key) {
            return $this->loadFromDatabase($group, $key);
        }
        return $this->loadGroupFromDatabase($group);
    }

    public function loadGroupFromDatabase($group)
    {
        return $this->em->getRepository('PAdminBundle:AdminConfig')->createQueryBuilder('ac')
            ->where('ac.group = :group')
            ->setParameter('group', $group)
            ->getQuery()
            ->useResultCache(true, 86400, self::CACHE_REGION)
            ->getResult()
            ;
    }

    public function loadFromDatabase($group, $key = null)
    {
        return $this->em->getRepository('PAdminBundle:AdminConfig')->createQueryBuilder('ac')
            ->where('ac.group = :group')
            ->andWhere('ac.key = :key')
            ->setParameters(array(
                'group' => $group,
                'key' => $key,
            ))
            ->getQuery()
            ->useResultCache(true, 86400, self::CACHE_REGION)
            ->getOneOrNullResult()
            ;
    }
}
