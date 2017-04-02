<?php

namespace P\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use P\AdminBundle\Entity\Crontab;
use P\AdminBundle\Form\CrontabType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Crontab controller.
 *
 */
class CrontabController extends Controller
{

    /**
     * Lists all Crontab entities.
     *
     */
    public function indexAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('PAdminBundle:Crontab')->createQueryBuilder('crontab')->orderBy('crontab.id', 'desc');

        $count = $em->getRepository('PAdminBundle:Crontab')->createQueryBuilder('crontab')
            ->select('COUNT(crontab.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;

        $crontab = new Crontab();

        $form = $this->createFormBuilder($crontab)
            ->add('submit', SubmitType::class, array('label' => 'query', 'attr' => array('class' => 'btn btn-primary')))
            ->setAction($this->generateUrl('crontab'))
            ->setMethod('POST')
            ->getForm()
            ;


        $form->handleRequest($request);
        if($form->isSubmitted()) {
        }

        list($entities, $pagination) = $this->get('p.paginator')->query($qb, $page, null, $count);
        $tools = $this->get('p.paginator')->renderView($pagination);


        return $this->render('PAdminBundle:Crontab:index.html.twig', array(
            'entities' => $entities,
            'tools' => $tools,
            'form' => $form->createView(),
        ));
    }
    /**
     * Creates a new Crontab entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Crontab();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crontab_show', array('id' => $entity->getId())));
        }

        return $this->render('PAdminBundle:Crontab:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Crontab entity.
     *
     * @param Crontab $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Crontab $entity)
    {
        $form = $this->createForm(CrontabType::class, $entity, array(
            'action' => $this->generateUrl('crontab_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new Crontab entity.
     *
     */
    public function newAction()
    {
        $entity = new Crontab();
        $form   = $this->createCreateForm($entity);

        return $this->render('PAdminBundle:Crontab:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Crontab entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:Crontab')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Crontab entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PAdminBundle:Crontab:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Crontab entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:Crontab')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Crontab entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PAdminBundle:Crontab:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Crontab entity.
    *
    * @param Crontab $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Crontab $entity)
    {
        $form = $this->createForm(CrontabType::class, $entity, array(
            'action' => $this->generateUrl('crontab_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'update', 'translation_domain' => 'messages', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    /**
     * Edits an existing Crontab entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:Crontab')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Crontab entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setUpdatedAt(new \DateTime());
            $em->flush();

            return $this->redirect($this->generateUrl('crontab_edit', array('id' => $id)));
        }

        return $this->render('PAdminBundle:Crontab:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Crontab entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PAdminBundle:Crontab')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Crontab entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('crontab'));
    }

    /**
     * Creates a form to delete a Crontab entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('crontab_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'delete', 'translation_domain' => 'messages', 'attr' => array('class' => 'btn btn-danger delete-action')))
            ->getForm()
        ;
    }
}
