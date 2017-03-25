<?php

namespace P\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\CallbackTransformer;

class DatePickerType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'widget' => 'single_text',
            'view_format' => 'Y-m-d',
            'date_format' => 'yyyy-mm-dd',
            'startView' => 'month',
            'endView' => 'month',
            'attr' => array(
                'readonly' => true,
                'disabled' => false,
            ),
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        if(!($data instanceof \DateTime)) {
            $builder->setData(new \DateTime($data));
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['startView'] = $options['startView'];
        $view->vars['endView'] = $options['endView'];
        $view->vars['view_format'] = $options['view_format'];
        $view->vars['date_format'] = $options['date_format'];
    }

    public function getBlockPrefix()
    {
        return 'p_datepicker';
    }

    public function getParent()
    {
        return 'date';
    }
}
