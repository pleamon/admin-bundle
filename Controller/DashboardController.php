<?php

namespace P\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * ArticleTag controller.
 *
 */
class DashboardController extends Controller
{
    public function indexAction()
    {
        return $this->render('PAdminBundle:Dashboard:index.html.twig');
    }
}
