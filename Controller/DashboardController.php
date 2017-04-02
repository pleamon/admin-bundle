<?php

namespace P\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use P\AdminBundle\Entity\DashboardWidget;

/**
 * ArticleTag controller.
 *
 */
class DashboardController extends Controller
{
    public function indexAction()
    {
        /*
        $email = $this->getUser()->getEmail();
        dump($email);
        dump(md5($email));
        exit;
         */
        $widgets = $this->get('p.admin.dashboard')->getWidgets();
        return $this->render('PAdminBundle:Dashboard:index.html.twig', array(
            'widgets' => $widgets,
        ));
    }

    public function widgetSwitchAction(Request $request)
    {
        $widgetId = $request->get('widgetId');
        $enabled = $request->get('enabled');

        $em = $this->getDoctrine()->getManager();
        $widget = $em->getRepository('PAdminBundle:DashboardWidget')->createQueryBuilder('dw')
            ->where('dw.widgetId = :widgetId')
            ->setParameter('widgetId', $widgetId)
            ->join('dw.users', 'u')
            ->andWhere('u = :user')
            ->setParameter('user', $this->getUser())
            ->getQuery()
            ->getOneOrNullResult()
            ;
        if(empty($widget)) {
            $_widget = $this->get('p.admin.dashboard')->get($widgetId);
            if(empty($_widget)) {
                return new JsonResponse(array('status' => 'failed'));
            }

            $widget = new DashboardWidget();
            $widget->setWidgetId($_widget->widgetId);
            $widget->setWidgetName($_widget->widgetName);
            $widget->addUser($this->getUser());
        }
        $widget->setEnabled($enabled == 'false' || empty($enabled) ? false : true);
        $em->persist($widget);
        $em->flush();
        return new JsonResponse(array('status' => 'success'));
    }
}
