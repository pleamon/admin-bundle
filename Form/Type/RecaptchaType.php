<?php

namespace P\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

class RecaptchaType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'lang' => 'zh_CN',
            'mapped' => false,
            'sitekey' => null,
            'secret' => null
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['sitekey'] = $options['sitekey'];
        $view->vars['lang'] = $options['lang'];
    }

    public function getBlockPrefix()
    {
        return 'p_recaptcha';
    }
}
