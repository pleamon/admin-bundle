<?php

namespace P\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Article controller.
 *
 */
class AdminConfigController extends Controller
{
    public function indexAction(Request $request)
    {
        $forms = array();
        $groups = $this->get('p.admin.core.config')->getGroups();

        $formBuilder = $this->createFormBuilder()
            ->setMethod('POST')
            ->setAction($this->generateUrl('p_admin_config'))
            ->add('submit', SubmitType::class, array('label' => 'update', 'attr' => array('class' => 'btn btn-outline btn-primary')))
            ;

        foreach($groups as $groupName => $configs) {
            $formBuilder->add($groupName, FormType::class);
            $formGroup = $formBuilder->get($groupName);
            foreach($configs as $config) {
                $key = $config->getKey();
                $type = $config->getType();
                $formGroup
                    ->add($key, $type, array_merge(array(
                        'required' => false,
                        'data' => $config->getValue(),
                        'label' => $config->getName(),
                        'help' => $config->getDescription(),
                    ), $config->getOptions()))
                    ;
            }
        }
        $form = $formBuilder->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            foreach($form->getData() as $groupName => $group) {
                foreach($group as $name => $value) {
                    $this->get('p.admin.core.config')->set($groupName, $name, $value);
                }
            }
            $this->get('p.admin.core.config')->save();
            return $this->redirectToRoute('p_admin_config');
        }
        return $this->render('PAdminBundle:AdminConfig:index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function clearCacheAction()
    {
        $this->get('p.admin.core.config')->clearCache();
        return $this->redirectToRoute('p_admin_config');
    }
}
