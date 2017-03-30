<?php

namespace P\AdminBundle\Component\TwigExtension;

class MarkdownParsedownExtension extends \Twig_Extension
{
    private $parsedown;
    public function __construct($parsedown)
    {
        $this->parsedown = $parsedown;
    }
    public function getFilters()
    {
        return array(
            //new \Twig_SimpleFilter('markdown', array($this, 'markdown')),
            new \Twig_SimpleFilter('markdown', array($this->parsedown, 'text')),
        );
    }

    /*
    public function markdown($text)
    {
        return $this->parsedown->text($text);
    }
     */

    public function getName()
    {
        return 'p_admin_markdown_extension';
    }
}

