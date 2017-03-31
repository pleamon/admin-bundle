<?php

namespace P\AdminBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TextTypeExtension extends AbstractTypeExtension
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array('help'));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(array_key_exists('help', $options)) {
            $view->vars['help'] = $options['help'];
        }
    }

    public function getExtendedType()
    {
        return TextType::class;
    }
}
