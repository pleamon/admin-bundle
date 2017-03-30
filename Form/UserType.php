<?php

namespace P\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        $builder
            ->add('username', null, array('label' => 'user.username'))
            ->add('email', null, array('label' => 'user.email'))
            ->add('plainPassword', RepeatedType::class, array(
                'required' => empty($data) || !$data->getId(),
                'type' => PasswordType::class,
                'options' => array('translation_domain' => 'PUserBundle'),
                'first_options' => array('label' => 'user.password'),
                'second_options' => array('label' => 'user.confirm_password'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('groups', null, array('label' => 'user.group'))
            ->add('enabled', null, array('label' => 'user.enabled'))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'P\UserBundle\Entity\User',
            'translation_domain' => 'PUserBundle',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'p_adminbundle_user';
    }


}

