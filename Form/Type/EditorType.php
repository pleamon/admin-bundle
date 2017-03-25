<?php

namespace P\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\CallbackTransformer;

class EditorType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'widget' => 'textarea',
            'attr' => array(
                'class' => "p_editor markdown-editor form-control"
            ),
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(!in_array('class', $view->vars['attr'])) {
            $view->vars['attr']['class'] = '';
        }
        $view->vars['attr']['class'] .= ' p_editor markdown-editor form-control';
    }

    public function getName()
    {
        return 'p_editor';
    }

    public function getBlockPrefix()
    {
        return 'textarea';
    }
}
