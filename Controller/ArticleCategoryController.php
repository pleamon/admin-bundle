<?php

namespace P\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use P\AdminBundle\Entity\ArticleCategory;
use P\AdminBundle\Form\ArticleCategoryType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * ArticleCategory controller.
 *
 */
class ArticleCategoryController extends Controller
{

    /**
     * Lists all ArticleCategory entities.
     *
     */
    public function indexAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('PAdminBundle:ArticleCategory')->createQueryBuilder('articlecategory');

        $count = $em->getRepository('PAdminBundle:ArticleCategory')->createQueryBuilder('articlecategory')
            ->select('COUNT(articlecategory.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;

        $articlecategory = new ArticleCategory();

        $form = $this->createFormBuilder($articlecategory)
            ->add('submit', SubmitType::class, array('label' => 'query', 'attr' => array('class' => 'btn btn-primary')))
            ->setAction($this->generateUrl('articlecategory'))
            ->setMethod('POST')
            ->getForm()
            ;


        $form->handleRequest($request);
        if($form->isSubmitted()) {
        }

        list($entities, $pagination) = $this->get('p.paginator')->query($qb, $page, null, $count);
        $tools = $this->get('p.paginator')->renderView($pagination);


        return $this->render('PAdminBundle:ArticleCategory:index.html.twig', array(
            'entities' => $entities,
            'tools' => $tools,
            'form' => $form->createView(),
        ));
    }
    /**
     * Creates a new ArticleCategory entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ArticleCategory();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('articlecategory_show', array('id' => $entity->getId())));
        }

        return $this->render('PAdminBundle:ArticleCategory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ArticleCategory entity.
     *
     * @param ArticleCategory $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ArticleCategory $entity)
    {
        $form = $this->createForm(ArticleCategoryType::class, $entity, array(
            'action' => $this->generateUrl('articlecategory_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

    /**
     * Displays a form to create a new ArticleCategory entity.
     *
     */
    public function newAction()
    {
        $entity = new ArticleCategory();
        $form   = $this->createCreateForm($entity);

        return $this->render('PAdminBundle:ArticleCategory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ArticleCategory entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:ArticleCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleCategory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PAdminBundle:ArticleCategory:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ArticleCategory entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:ArticleCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleCategory entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PAdminBundle:ArticleCategory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ArticleCategory entity.
    *
    * @param ArticleCategory $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ArticleCategory $entity)
    {
        $form = $this->createForm(ArticleCategoryType::class, $entity, array(
            'action' => $this->generateUrl('articlecategory_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'update', 'translation_domain' => 'messages', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }
    /**
     * Edits an existing ArticleCategory entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PAdminBundle:ArticleCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ArticleCategory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('articlecategory_edit', array('id' => $id)));
        }

        return $this->render('PAdminBundle:ArticleCategory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ArticleCategory entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PAdminBundle:ArticleCategory')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ArticleCategory entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('articlecategory'));
    }

    /**
     * Creates a form to delete a ArticleCategory entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('articlecategory_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'delete', 'translation_domain' => 'messages', 'attr' => array('class' => 'btn btn-danger delete-action')))
            ->getForm()
        ;
    }
}
