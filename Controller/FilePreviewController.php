<?php

namespace P\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Team\TaskBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Response;

class FilePreviewController extends Controller
{
    public function indexAction(Request $request, $category, $filename)
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
        $suffixs = array(
            'office' => array('docx', 'docm', 'dotm', 'dotx', 'xlsx', 'xlsb', 'xls', 'xlsm', 'pptx', 'ppsx', 'ppt', 'pps', 'pptm', 'potm', 'ppam', 'potx', 'ppsm'), 
            'image' => array('png', 'jpg', 'jpeg', 'gif', 'bmp'),
            'pdf' => array('pdf')
        );
        $type = '';
        foreach($suffixs as $typeName => $suffix) {
            if(in_array($file->getSuffix(), $suffix)) {
                $type = $typeName;
                break;
            }
        }

        switch($type) {
        case 'office': return $this->officePreview($request, $file); break;
        case 'image': return $this->imagePreview($file); break;
        case 'pdf': return $this->pdfPreview($file); break;
        }
    }

    public function pdfPreview($file)
    {
        $filename = $file->getAbsolute();
        $response = new Response();
        $response->headers->set('Content-type', mime_content_type($filename));
        $response->setContent(file_get_contents($filename));
        return $response;
    }

    public function imagePreview($file)
    {
        $filename = $file->getAbsolute();
        $response = new Response();
        $response->headers->set('Content-type', mime_content_type($filename));
        $response->setContent(file_get_contents($filename));
        return $response;
    }

    public function officePreview($request, $file)
    {
        $host = $request->server->get('HTTP_HOST');
        return $this->redirect("https://view.officeapps.live.com/op/view.aspx?src={$host}/{$file->getAccessPath()}");
    }
}
