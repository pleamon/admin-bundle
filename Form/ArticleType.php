<?php

namespace P\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use P\AdminBundle\Form\Type\RichTextType;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', null, array('label' => 'article.category'))
            ->add('tags', null, array('label' => 'article.tags'))
            ->add('title', null, array('label' => 'article.title'))
            ->add('content', RichTextType::class, array('label' => 'article.content'))
            ->add('status', ChoiceType::class, array('label' => 'article.status', 'choices' => array(
                '隐藏' => 0,
                '正常' => 1,
            ), 'data' => 1))
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'P\AdminBundle\Entity\Article',
            'translation_domain' => 'PAdminBundle',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'p_adminbundle_article';
    }


}

