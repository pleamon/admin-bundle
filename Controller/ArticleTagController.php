<?php

namespace P\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use P\AdminBundle\Entity\ArticleTag;
use P\AdminBundle\Form\ArticleTagType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * ArticleTag controller.
 *
 */
class ArticleTagController extends Controller
{

    /**
     * Lists all ArticleTag entities.
     *
     */
    public function indexAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('PAdminBundle:ArticleTag')->createQueryBuilder('articletag');

        $count = $em->getRepository('PAdminBundle:ArticleTag')->createQueryBuilder('articletag')
            ->select('COUNT(articletag.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;

        $articletag = new ArticleTag();

        $form = $this->createFormBuilder($articletag)
            ->add('submit', SubmitType::class, array('label' => 'query', 'attr' => array('class' => 'btn btn-primary')))
            ->setAction($this->generateUrl('articletag'))
            ->setMethod('POST')
            ->getForm()
            ;


        $form->handleRequest($request);
        if($form->isSubmitted()) {
        }

        list($entities, $pagination) = $this->get('p.paginator')->query($qb, $page, null, $count);
        $tools = $this->get('p.paginator')->renderView($pagination);


        return $this->render('PAdminBundle:ArticleTag:index.html.twig', array(
            'entities' => $entities,
            'tools' => $tools,
            'form' => $form->createView(),
        ));
    }
    /**
     * Creates a new ArticleTag entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ArticleTag();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('articletag_show', array('id' => $entity->getId())));
        }

        return $this->render('PAdminBundle:ArticleTag:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ArticleTag entity.
     *
     * @param ArticleTag $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ArticleTag $entity)
    {
        $form = $this->createForm(ArticleTagType::class, $entity, array(
            'action' => $this->generateUrl('articletag_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new ArticleTag entity.
     *
     */
    public function newAction()
    {
        $entity = new ArticleTag();
        $form   = $this->createCreateForm($entity);

        return $this->render('PAdminBundle:ArticleTag:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ArticleTag entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:ArticleTag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleTag entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PAdminBundle:ArticleTag:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ArticleTag entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:ArticleTag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleTag entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PAdminBundle:ArticleTag:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ArticleTag entity.
    *
    * @param ArticleTag $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ArticleTag $entity)
    {
        $form = $this->createForm(ArticleTagType::class, $entity, array(
            'action' => $this->generateUrl('articletag_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'update', 'translation_domain' => 'messages', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    /**
     * Edits an existing ArticleTag entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:ArticleTag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleTag entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('articletag_edit', array('id' => $id)));
        }

        return $this->render('PAdminBundle:ArticleTag:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ArticleTag entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PAdminBundle:ArticleTag')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ArticleTag entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('articletag'));
    }

    /**
     * Creates a form to delete a ArticleTag entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('articletag_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'delete', 'translation_domain' => 'messages', 'attr' => array('class' => 'btn btn-danger delete-action')))
            ->getForm()
        ;
    }
}
