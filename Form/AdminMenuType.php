<?php

namespace P\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityRepository;

use P\AdminBundle\Form\Type\IconType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AdminMenuType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $options['container'];

        $router = $container->get('router')->getRouteCollection();
        $routes = array();

        foreach($router->all() as $name => $route) {
            if($name[0] != '_') {
                $routes[$name] = $name;
            }
        }

        $data = $builder->getData();

        $builder
            ->add('parent', EntityType::class, array('label' => 'adminmenu.parent',
                'required' => false,
                'class' => 'PAdminBundle:AdminMenu',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('am')
                        ->andWhere('am.parent IS NULL');
            }))
            ->add('route', ChoiceType::class, array('label' => 'adminmenu.route', 'choices' => $routes, 'required' => false))
            ->add('name', null, array('label' => 'adminmenu.name'))
            ->add('text', null, array('label' => 'adminmenu.text'))
            ->add('icon', IconType::class, array('label' => 'adminmenu.icon', 'required' => false))
            ->add('roles', null, array('label' => 'adminmenu.roles'))
            ->add('sort', null, array('label' => 'adminmenu.sort'))
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('container');
        $resolver->setDefaults(array(
            'data_class' => 'P\AdminBundle\Entity\AdminMenu',
            'translation_domain' => 'PAdminBundle',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'p_adminbundle_adminmenu';
    }
}

