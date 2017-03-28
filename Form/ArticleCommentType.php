<?php

namespace P\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleCommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('article', null, array('label' => 'articlecomment.article'))
            ->add('title', null, array('label' => 'articlecomment.title'))
            ->add('content', null, array('label' => 'articlecomment.content'))
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'P\AdminBundle\Entity\ArticleComment',
            'translation_domain' => 'PAdminBundle',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'p_adminbundle_articlecomment';
    }


}

