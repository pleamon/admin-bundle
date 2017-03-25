<?php

namespace P\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FileDownloadController extends Controller
{

    public function downloadAction($category, $filename)
    {
        $em = $this->getDoctrine();

        $file = $em->getRepository('PAdminBundle:File')->createQueryBuilder('f')
            ->leftJoin('f.category', 'c')
            ->where('c.name = :categoryName')
            ->andWhere('f.filename = :filename')
            ->setParameter('categoryName', $category)
            ->setParameter('filename', $filename)
            ->getQuery()
            ->getSingleResult()
            ;

        if(empty($file)) {
            throw $this->createNotFoundException('The file does not found');
        }

        $filepath = $file->getAbsolute();
        $filename = $file->getFilename();
        $response = new Response();
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', mime_content_type($filepath));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '";');
        $response->headers->set('Content-length', filesize($filepath));
        $response->setContent(file_get_contents($filepath));
        $response->sendHeaders();

        return $response;
    }
}
