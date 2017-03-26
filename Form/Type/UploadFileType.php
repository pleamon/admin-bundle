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

class UploadFileType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('container');
        $resolver->setDefaults(array(
            'class' => 'P\AdminBundle\Entity\File',
            'attr' => array(
                'style' => 'height: 150px; width: 150px;',
                'class' => 'form-control',
            ),
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, array(
                'label' => 'file.category',
                'translation_domain' => 'PAdminBundle',
                'class' => 'PAdminBundle:FileCategory',
                'required' => true,
            ))
            ->add('tags', EntityType::class, array(
                'label' => 'file.tags',
                'translation_domain' => 'PAdminBundle',
                'class' => 'PAdminBundle:FileTag',
                'multiple' => true,
                'required' => false,
            ))
        ;

        $builder
            ->addModelTransformer(new CallbackTransformer(
                function ($data) {
                    if($data && empty($data->getCategory()->getId())) {
                        $data->setCategory(null);
                    }
                    return $data;
                },
                function ($data) use ($builder, $options){
                    $container = $options['container'];
                    $request = $container->get('request_stack')->getCurrentRequest();
                    $uploadFiles = $request->files;
                    $uploadFile = $uploadFiles->get($builder->getName());

                    if($uploadFile) {
                        $category = null;
                        $tags = null;
                        if(is_array($data)) {
                            $category = $data['category'];
                            $tags = $data['tags'];
                            $data = new File($uploadFile, $category, $tags);
                        } else if(is_object($data)) {
                            $category = $data->getCategory();
                            $tags = $data->getTags();
                            $data->setFile($uploadFile);
                            $data->setCategory = $category;
                            $data->setTags = $tags;
                            $data->upload();
                        }
                        return $data;
                    } else {
                        return $data ? $data : null;
                    }
                }
            ));

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['parent'] = $view->parent;
    }

    public function getBlockPrefix()
    {
        return 'p_upload_file';
    }
}
