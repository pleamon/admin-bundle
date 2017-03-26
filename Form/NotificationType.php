<?php

namespace P\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use P\AdminBundle\Form\Type\RichTextType;
use P\AdminBundle\Form\Type\IconType;

class NotificationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $options['container'];
        $builder
            ->add('icon', IconType::class, array('label' => 'notification.icon'))
            ->add('category', null, array('label' => 'notification.category'))
            ->add('level', ChoiceType::class, array('label' => 'notification.level', 'choices' => array_flip($container->getParameter('p.notification.levels'))))
            ->add('title', null, array('label' => 'notification.title'))
            ->add('message', RichTextType::class, array('label' => 'notification.message'))
            ->add('status', ChoiceType::class, array('label' => 'notification.status', 'choices' => array_flip($container->getParameter('p.notification.status')), 'data' => 1))
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('container');
        $resolver->setDefaults(array(
            'data_class' => 'P\AdminBundle\Entity\Notification',
            'translation_domain' => 'PAdminBundle',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'p_adminbundle_notification';
    }


}

