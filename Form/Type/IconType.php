<?php

namespace P\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class IconType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => 'P\AdminBundle\Entity\Icon',
            'element' => '',
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
    }

    public function getBlockPrefix()
    {
        return 'p_icon';
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
