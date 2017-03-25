<?php

namespace P\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use P\AdminBundle\Entity\NotificationCategory;
use P\AdminBundle\Form\NotificationCategoryType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * NotificationCategory controller.
 *
 */
class NotificationCategoryController extends Controller
{

    /**
     * Lists all NotificationCategory entities.
     *
     */
    public function indexAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('PAdminBundle:NotificationCategory')->createQueryBuilder('notificationcategory');

        $count = $em->getRepository('PAdminBundle:NotificationCategory')->createQueryBuilder('notificationcategory')
            ->select('COUNT(notificationcategory.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;

        $notificationcategory = new NotificationCategory();

        $form = $this->createFormBuilder($notificationcategory)
            ->add('submit', SubmitType::class, array('label' => 'query', 'attr' => array('class' => 'btn btn-primary')))
            ->setAction($this->generateUrl('notificationcategory'))
            ->setMethod('POST')
            ->getForm()
            ;


        $form->handleRequest($request);
        if($form->isSubmitted()) {
        }

        list($entities, $pagination) = $this->get('p.paginator')->query($qb, $page, null, $count);
        $tools = $this->get('p.paginator')->renderView($pagination);


        return $this->render('PAdminBundle:NotificationCategory:index.html.twig', array(
            'entities' => $entities,
            'tools' => $tools,
            'form' => $form->createView(),
        ));
    }
    /**
     * Creates a new NotificationCategory entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new NotificationCategory();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('notificationcategory_show', array('id' => $entity->getId())));
        }

        return $this->render('PAdminBundle:NotificationCategory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a NotificationCategory entity.
     *
     * @param NotificationCategory $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(NotificationCategory $entity)
    {
        $form = $this->createForm(NotificationCategoryType::class, $entity, array(
            'action' => $this->generateUrl('notificationcategory_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new NotificationCategory entity.
     *
     */
    public function newAction()
    {
        $entity = new NotificationCategory();
        $form   = $this->createCreateForm($entity);

        return $this->render('PAdminBundle:NotificationCategory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a NotificationCategory entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:NotificationCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NotificationCategory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PAdminBundle:NotificationCategory:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing NotificationCategory entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:NotificationCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NotificationCategory entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PAdminBundle:NotificationCategory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a NotificationCategory entity.
    *
    * @param NotificationCategory $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(NotificationCategory $entity)
    {
        $form = $this->createForm(NotificationCategoryType::class, $entity, array(
            'action' => $this->generateUrl('notificationcategory_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'update', 'translation_domain' => 'messages', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    /**
     * Edits an existing NotificationCategory entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:NotificationCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NotificationCategory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('notificationcategory_edit', array('id' => $id)));
        }

        return $this->render('PAdminBundle:NotificationCategory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a NotificationCategory entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PAdminBundle:NotificationCategory')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find NotificationCategory entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('notificationcategory'));
    }

    /**
     * Creates a form to delete a NotificationCategory entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('notificationcategory_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'delete', 'translation_domain' => 'messages', 'attr' => array('class' => 'btn btn-danger delete-action')))
            ->getForm()
        ;
    }
}
