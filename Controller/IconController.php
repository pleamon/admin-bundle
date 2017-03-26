<?php

namespace P\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use P\AdminBundle\Entity\Icon;
use P\AdminBundle\Form\IconType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Icon controller.
 *
 */
class IconController extends Controller
{

    /**
     * Lists all Icon entities.
     *
     */
    public function indexAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('PAdminBundle:Icon')->createQueryBuilder('icon');

        $count = $em->getRepository('PAdminBundle:Icon')->createQueryBuilder('icon')
            ->select('COUNT(icon.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;

        $icon = new Icon();

        $form = $this->createFormBuilder($icon)
            ->add('name', null, array('label' => 'icon.name', 'translation_domain' => 'PAdminBundle'))
            ->add('type', null, array('label' => 'icon.type', 'translation_domain' => 'PAdminBundle'))
            ->add('submit', SubmitType::class, array('label' => 'query', 'attr' => array('class' => 'btn btn-primary')))
            ->setAction($this->generateUrl('icon'))
            ->setMethod('POST')
            ->getForm()
            ;


        $form->handleRequest($request);
        if($form->isSubmitted()) {
            if($icon->getType()) {
                $qb->andWhere('icon.type = :type')
                    ->setParameter('type', $icon->getType())
                    ;
            }
            if($icon->getName()) {
                $qb->andWhere('icon.name = :name')
                    ->setParameter('name', $icon->getName())
                    ;
            }
        }

        list($entities, $pagination) = $this->get('p.paginator')->query($qb, $page, null, $count);
        $tools = $this->get('p.paginator')->renderView($pagination);


        return $this->render('PAdminBundle:Icon:index.html.twig', array(
            'entities' => $entities,
            'tools' => $tools,
            'form' => $form->createView(),
        ));
    }
    /**
     * Creates a new Icon entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Icon();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('icon_show', array('id' => $entity->getId())));
        }

        return $this->render('PAdminBundle:Icon:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Icon entity.
     *
     * @param Icon $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Icon $entity)
    {
        $form = $this->createForm(IconType::class, $entity, array(
            'action' => $this->generateUrl('icon_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Icon entity.
     *
     */
    public function newAction()
    {
        $entity = new Icon();
        $form   = $this->createCreateForm($entity);

        return $this->render('PAdminBundle:Icon:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Icon entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:Icon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Icon entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PAdminBundle:Icon:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Icon entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:Icon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Icon entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PAdminBundle:Icon:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Icon entity.
    *
    * @param Icon $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Icon $entity)
    {
        $form = $this->createForm(IconType::class, $entity, array(
            'action' => $this->generateUrl('icon_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'update', 'translation_domain' => 'messages', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    /**
     * Edits an existing Icon entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:Icon')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Icon entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('icon_edit', array('id' => $id)));
        }

        return $this->render('PAdminBundle:Icon:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Icon entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PAdminBundle:Icon')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Icon entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('icon'));
    }

    /**
     * Creates a form to delete a Icon entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('icon_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'delete', 'translation_domain' => 'messages', 'attr' => array('class' => 'btn btn-danger delete-action')))
            ->getForm()
        ;
    }
}
