<?php

namespace P\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use P\AdminBundle\Entity\File;
use P\AdminBundle\Form\FileType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * File controller.
 *
 */
class FileHomeController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $tagId = $request->get('tag');
        $categoryId = $request->get('category');
        $type = $request->get('type');

        $categories = $em->getRepository('PAdminBundle:FileCategory')->findAll();
        $tags = $em->getRepository('PAdminBundle:FileTag')->findAll();
        $types = $this->getParameter('p.file_types');

        $qb = $em->getRepository('PAdminBundle:File')->createQueryBuilder('file');

        if($categoryId) {
            $qb
                ->join('file.category', 'c')
                ->andWhere('c = :category')
                ->setParameter('category', $categoryId)
                ;
        }
        if($tagId) {
            $qb
                ->join('file.tags', 't')
                ->andWhere('t.id = :tag')
                ->setParameter('tag', $tagId)
                ;
        }
        if($type) {
            $qb
                ->andWhere('file.suffix in (:types)')
                ->setParameter('types', $types[$type])
                ;
        }


        $files = $qb->getQuery()
            ->getResult();


        $entity = new File();
        $form   = $this->createCreateForm($entity);

        return $this->render('PAdminBundle:FileHome:index.html.twig', array(
            'categories' => $categories,
            'tags' => $tags,
            'files' => $files,
            'tagId' => $tagId,
            'categoryId' => $categoryId,
            'form' => $form->createView(),
            'types' => $types,
            'type' => $type,
        ));
    }

    private function createCreateForm(File $entity)
    {
        $form = $this->createForm(FileType::class, $entity, array(
            'action' => $this->generateUrl('file_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'create', 'attr' => array('class' => 'btn btn-primary')));

        return $form;
    }

}
