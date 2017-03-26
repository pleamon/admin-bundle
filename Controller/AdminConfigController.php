<?php

namespace P\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Article controller.
 *
 */
class AdminConfigController extends Controller
{
    public function indexAction()
    {
        return $this->render('PAdminBundle:AdminConfig:index.html.twig');
    }
}
