<?php

namespace P\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CrontabType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('schedule', null, array('label' => 'crontab.schedule'))
            ->add('type', ChoiceType::class, array('label' => 'crontab.type', 'choices' => array(
                'php' => 0,
                'shell' => 1,
            )))
            ->add('execute', null, array('label' => 'crontab.execute'))
            ->add('parameter', null, array('label' => 'crontab.parameter'))
            ->add('status', null, array('label' => 'crontab.status'))
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'P\AdminBundle\Entity\Crontab',
            'translation_domain' => 'PAdminBundle',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'p_adminbundle_crontab';
    }


}

