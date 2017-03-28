<?php

namespace P\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use P\AdminBundle\Entity\ArticleComment;
use P\AdminBundle\Form\ArticleCommentType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * ArticleComment controller.
 *
 */
class ArticleCommentController extends Controller
{

    /**
     * Lists all ArticleComment entities.
     *
     */
    public function indexAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('PAdminBundle:ArticleComment')->createQueryBuilder('articlecomment')->orderBy('articlecomment.id', 'desc');

        $count = $em->getRepository('PAdminBundle:ArticleComment')->createQueryBuilder('articlecomment')
            ->select('COUNT(articlecomment.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;

        $articlecomment = new ArticleComment();

        $form = $this->createFormBuilder($articlecomment)
            ->add('submit', SubmitType::class, array('label' => 'query', 'attr' => array('class' => 'btn btn-primary')))
            ->setAction($this->generateUrl('articlecomment'))
            ->setMethod('POST')
            ->getForm()
            ;


        $form->handleRequest($request);
        if($form->isSubmitted()) {
        }

        list($entities, $pagination) = $this->get('p.paginator')->query($qb, $page, null, $count);
        $tools = $this->get('p.paginator')->renderView($pagination);


        return $this->render('PAdminBundle:ArticleComment:index.html.twig', array(
            'entities' => $entities,
            'tools' => $tools,
            'form' => $form->createView(),
        ));
    }
    /**
     * Creates a new ArticleComment entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ArticleComment();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('articlecomment_show', array('id' => $entity->getId())));
        }

        return $this->render('PAdminBundle:ArticleComment:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ArticleComment entity.
     *
     * @param ArticleComment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ArticleComment $entity)
    {
        $form = $this->createForm(ArticleCommentType::class, $entity, array(
            'action' => $this->generateUrl('articlecomment_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new ArticleComment entity.
     *
     */
    public function newAction()
    {
        $entity = new ArticleComment();
        $form   = $this->createCreateForm($entity);

        return $this->render('PAdminBundle:ArticleComment:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ArticleComment entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:ArticleComment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleComment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PAdminBundle:ArticleComment:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ArticleComment entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:ArticleComment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleComment entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PAdminBundle:ArticleComment:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ArticleComment entity.
    *
    * @param ArticleComment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ArticleComment $entity)
    {
        $form = $this->createForm(ArticleCommentType::class, $entity, array(
            'action' => $this->generateUrl('articlecomment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'update', 'translation_domain' => 'messages', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    /**
     * Edits an existing ArticleComment entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:ArticleComment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleComment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('articlecomment_edit', array('id' => $id)));
        }

        return $this->render('PAdminBundle:ArticleComment:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ArticleComment entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PAdminBundle:ArticleComment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ArticleComment entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('articlecomment'));
    }

    /**
     * Creates a form to delete a ArticleComment entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('articlecomment_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'delete', 'translation_domain' => 'messages', 'attr' => array('class' => 'btn btn-danger delete-action')))
            ->getForm()
        ;
    }
}
