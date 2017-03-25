<?php

namespace P\AdminBundle\Service;

class MarkdownParsedownExtension extends \Twig_Extension
{
    private $container;
    public function __construct($container)
    {
        $this->container = $container;
    }
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('markdown', array($this, 'markdown')),
        );
    }

    public function markdown($text)
    {
        return $this->container->get('p.markdown')->text($text);
    }

    public function getName()
    {
        return 'pmarkdown_extension';
    }
}

