<?php

namespace P\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'file_path' => '',
            'attr' => array(
                'style' => 'height: 300px',
            ),
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $data = $form->getData();
        $path = null;
        if($data instanceof \P\FileBundle\Entity\File) {
            $dir = $data->getCategory()->getDir();
            $path = $dir . $data->getMd5();
        }
        if(is_string($data)) {
            $path = $data;
        }
        if(array_key_exists('file_path', $options)) {
            $path = $options['file_path'];
        }
        $view->vars['file_path'] = $path;
        /*
        if(array_key_exists('image_path', $options)) {
            $parentData = $form->getParent()->getData();
            if(null != $parentData) {
                $accessor = PropertyAccess::createPropertyAccessor();
                $imageUrl = $accessor->getValue($parentData, $options['images_path']);
            } else {
                $imageUrl = null;
            }
            $view->vars['images_url'] = $imageUrl;
        }
         */
    }

    public function getBlockPrefix()
    {
        return 'p_image';
    }
}
