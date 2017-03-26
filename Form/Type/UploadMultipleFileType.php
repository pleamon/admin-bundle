<?php

namespace P\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\CallbackTransformer;

use P\AdminBundle\Entity\File;
use P\AdminBundle\Entity\FileCategory;
use P\AdminBundle\Entity\FileTag;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UploadMultipleFileType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('container');
        $resolver->setDefaults(array(
            //'class' => 'Doctrine\Common\Collections\ArrayCollection',
            'data_class' => null,
            'attr' => array(
                'style' => 'height: 150px; width: 150px;',
                'class' => 'form-control',
            ),
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        $file_id = array();
        foreach($data as $file) {
            array_push($file_id, $file->getId());
        }
        $builder
            ->add('category', EntityType::class, array(
                'label' => 'file.category',
                'translation_domain' => 'PAdminBundle',
                'class' => 'PAdminBundle:FileCategory',
                'required' => false,
            ))
            ->add('tags', EntityType::class, array(
                'label' => 'file.tags',
                'translation_domain' => 'PAdminBundle',
                'class' => 'PAdminBundle:FileTag',
                'multiple' => true,
                'required' => false,
            ))
            ->add('file_id', ChoiceType::class, array('multiple' => true, 'expanded' => true, 'choices' => $file_id, 'attr' => array(
                'class' => 'hidden'
            ), 'data' => $file_id))
        ;

        $builder
            ->addModelTransformer(new CallbackTransformer(
                function ($data) use ($builder) {
                    $arr = array(
                        'files' => $data,
                        'category' => array(),
                        'tags' => array(),
                        'file_id' => array(),
                    );
                    return $arr;
                },
                function ($data) use ($builder, $options){
                    $container = $options['container'];
                    $request = $container->get('request_stack')->getCurrentRequest();
                    $uploadFiles = $request->files->get($builder->getName());

                    $category = $data['category'];
                    $tags = $data['tags'];
                    $files = array();
                    $file_id = $data['file_id'];
                    foreach($data['files'] as $file) {
                        if(in_array($file->getId(), $file_id)) {
                            array_push($files, $file);
                        }
                    }
                    if(count($uploadFiles) > 0) {
                        if(empty($category)) {
                            $container = $options['container'];
                            $em = $container->get('doctrine.orm.default_entity_manager');
                            $category = $em->getRepository('PAdminBundle:FileCategory')->findOneByKeyName('default');
                            if(empty($category)) {
                                $category = new FileCategory('default', 'default', 'default');
                                $em->persist($category);
                                $em->flush();
                            }
                        }
                        foreach($uploadFiles as $uploadFile) {
                            if($uploadFile) {
                                $file = new File($uploadFile, $category, $tags);
                                array_push($files, $file);
                            }
                        }
                    }
                    return $files;
                }
            ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['parent'] = $view->parent;
    }

    public function getBlockPrefix()
    {
        return 'p_multiple_upload_file';
    }
}
