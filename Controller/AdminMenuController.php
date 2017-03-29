<?php

namespace P\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use P\AdminBundle\Entity\AdminMenu;
use P\AdminBundle\Form\AdminMenuType;

use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * AdminMenu controller.
 *
 */
class AdminMenuController extends Controller
{

    public function listAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('PAdminBundle:AdminMenu')->createQueryBuilder('adminmenu')
            ->orderBy('adminmenu.parent', 'asc')
            ->addOrderBy('adminmenu.route', 'asc')
            ->addOrderBy('adminmenu.sort', 'desc')
            ;

        $count = $em->getRepository('PAdminBundle:AdminMenu')->createQueryBuilder('adminmenu')
            ->select('COUNT(adminmenu.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;

        $adminmenu = new AdminMenu();

        $form = $this->createFormBuilder($adminmenu)
            ->add('parent', EntityType::class, array('label' => 'adminmenu.parent', 'translation_domain' => 'PAdminBundle',
                'required' => false,
                'class' => 'PAdminBundle:AdminMenu',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('am')
                        ->andWhere('am.parent IS NULL');
                }
            ))
            ->add('name', null, array('label' => 'adminmenu.name', 'required' => false, 'translation_domain' => 'PAdminBundle'))
            ->add('text', null, array('label' => 'adminmenu.text', 'required' => false, 'translation_domain' => 'PAdminBundle'))
            ->add('route', null, array('label' => 'adminmenu.route', 'required' => false, 'translation_domain' => 'PAdminBundle'))
            ->add('submit', SubmitType::class, array('label' => 'query', 'attr' => array('class' => 'btn btn-primary')))
            ->setAction($this->generateUrl('adminmenu_list'))
            ->setMethod('POST')
            ->getForm()
            ;


        $form->handleRequest($request);
        if($form->isSubmitted()) {
            if($adminmenu->getParent()) {
                $qb->andWhere('adminmenu.parent = :parent')
                    ->setParameter('parent', $adminmenu->getParent())
                    ;
            }
            if($adminmenu->getName()) {
                $qb->andWhere('adminmenu.name = :name')
                    ->setParameter('name', $adminmenu->getName())
                    ;
            }
            if($adminmenu->getText()) {
                $qb->andWhere('adminmenu.text = :text')
                    ->setParameter('text', $adminmenu->getText())
                    ;
            }
            if($adminmenu->getRoute()) {
                $qb->andWhere('adminmenu.route = :route')
                    ->setParameter('route', $adminmenu->getRoute())
                    ;
            }
        }

        list($entities, $pagination) = $this->get('p.paginator')->query($qb, $page, null, $count);
        $tools = $this->get('p.paginator')->renderView($pagination);


        return $this->render('PAdminBundle:AdminMenu:list.html.twig', array(
            'entities' => $entities,
            'tools' => $tools,
            'form' => $form->createView(),
        ));
    }

    /**
     * Lists all AdminMenu entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PAdminBundle:AdminMenu')->createQueryBuilder('adminmenu')
            ->where('adminmenu.parent IS NULL')
            ->andWhere('adminmenu.enabled = 1')
            ->addOrderBy('adminmenu.sort', 'asc')
            ->getQuery()
            ->getResult()
            ;

        $unabledMenus = $em->getRepository('PAdminBundle:AdminMenu')->findByEnabled(0);

        $sorts = array();

        return $this->render('PAdminBundle:AdminMenu:index.html.twig', array(
            'entities' => $entities,
            'unabledMenus' => $unabledMenus,
            'sorts' => json_encode($sorts),
        ));
    }

    public function sortSaveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $sorts = $request->getContent();
        $builder = $em->createQueryBuilder();
        $builder->update('PAdminBundle:AdminMenu', 'am')->set('am.enabled', '0')->getQuery()->execute();;
        foreach(json_decode($sorts, 1) as $idx => $firstItem) {
            $first = $em->getRepository('PAdminBundle:AdminMenu')->find($firstItem['id']);
            $first->setSort($idx);
            $first->setParent(null);
            $first->setEnabled(true);
            $em->persist($first);
            if(array_key_exists('children', $firstItem)) {
                foreach($firstItem['children'] as $secondIdx => $secondItem) {
                    $second = $em->getRepository('PAdminBundle:AdminMenu')->find($secondItem['id']);
                    $second->setSort($secondIdx);
                    $second->setParent($first);
                    $second->setEnabled(true);
                    $em->persist($second);
                    if(array_key_exists('children', $secondItem)) {
                        foreach($secondItem['children'] as $thirdIdx => $thirdItem) {
                            $third = $em->getRepository('PAdminBundle:AdminMenu')->find($thirdItem['id']);
                            $third->setSort($thirdIdx);
                            $third->setParent($second);
                            $third->setEnabled(true);
                            $em->persist($second);
                        }
                    }
                }
            }
        }
        $em->flush();
        return new JsonResponse($sorts);
    }

    public function scanAction()
    {
        $em = $this->getDoctrine()->getManager();

        $this->get('p.admin.menu')->scan();

        $menus = $em->getRepository('PAdminBundle:AdminMenu')->findBy(array('enabled' => 0), array('sort' => 'asc'));

        $menusJson = $this->get('p.admin.pool')->entityToJson($menus);

        return new Response($menusJson);
    }

    /**
     * Creates a new AdminMenu entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new AdminMenu();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('adminmenu_show', array('id' => $entity->getId())));
        }

        return $this->render('PAdminBundle:AdminMenu:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a AdminMenu entity.
     *
     * @param AdminMenu $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AdminMenu $entity)
    {
        $form = $this->createForm(AdminMenuType::class, $entity, array(
            'container' => $this->container,
            'action' => $this->generateUrl('adminmenu_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new AdminMenu entity.
     *
     */
    public function newAction()
    {
        $entity = new AdminMenu();
        $form   = $this->createCreateForm($entity);

        return $this->render('PAdminBundle:AdminMenu:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminMenu entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:AdminMenu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdminMenu entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PAdminBundle:AdminMenu:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminMenu entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:AdminMenu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdminMenu entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PAdminBundle:AdminMenu:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a AdminMenu entity.
    *
    * @param AdminMenu $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(AdminMenu $entity)
    {
        $form = $this->createForm(AdminMenuType::class, $entity, array(
            'container' => $this->container,
            'action' => $this->generateUrl('adminmenu_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'update', 'translation_domain' => 'messages', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    /**
     * Edits an existing AdminMenu entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:AdminMenu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdminMenu entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('adminmenu_edit', array('id' => $id)));
        }

        return $this->render('PAdminBundle:AdminMenu:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a AdminMenu entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PAdminBundle:AdminMenu')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AdminMenu entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('adminmenu_list'));
    }

    /**
     * Creates a form to delete a AdminMenu entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('adminmenu_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'delete', 'translation_domain' => 'messages', 'attr' => array('class' => 'btn btn-danger delete-action')))
            ->getForm()
        ;
    }
}
