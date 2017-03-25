<?php

namespace P\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\CallbackTransformer;

use P\AdminBundle\Entity\Region;
use P\AdminBundle\Exception\NotFoundRegionException;


class RegionType extends AbstractType
{
    private $em;
    private $container;
    public $provinces;
    public $citys;
    public $streects;

    public function __construct($container)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine');

        $regionRepository = $this->em->getRepository('PAdminBundle:Region');
        $provinces = $regionRepository->createQueryBuilder('r')
            ->where('r.type = 1')
            ->getQuery()
            ->useResultCache(true, 86400, 'region_provinces')
            ->getResult();
        $citys = $regionRepository->createQueryBuilder('r')
            ->where('r.type = 2')
            ->getQuery()
            ->useResultCache(true, 86400, 'region_citys')
            ->getResult();
        $streects = $regionRepository->createQueryBuilder('r')
            ->where('r.type = 3')
            ->getQuery()
            ->useResultCache(true, 86400, 'region_streects')
            ->getResult();
        if(empty($provinces)) {
            throw new \Exception('region数据未导入，请执行[./app/console p:region:load]');
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => 'P\AdminBundle\Entity\Region',
            'choices' => $this->streects,
            'attr' => array(
                'style' => 'font-size: 25px',
            ),
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        $region = null;
        if(is_numeric($data)) {
            $region = $this->em->getRepository('PAdminBundle:Region')->find($data);
        } else if($data instanceof Region) {
            $region = $data;
        }
        if($data && $region) {
            $builder->setData($region);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $region = $form->getData();

        $selected_province = 0;
        $selected_city = 0;
        $selected_street = 0;
        if($region) {
            if($region->getParent()) {
                if($region->getParent()->getParent()) {
                    $selected_province = $region->getParent()->getParent()->getId();
                    $selected_city = $region->getParent()->getId();
                    $selected_street = $region->getId();
                } else {
                    $selected_province = $region->getParent()->getId();
                    $selected_city = $region->getId();
                }
            } else {
                $selected_province = $region->getId();
            }
        }
        $view->vars['selected_province'] = $selected_province;
        $view->vars['selected_city'] = $selected_city;
        $view->vars['selected_street'] = $selected_street;
    }

    public function getBlockPrefix()
    {
        return 'p_region';
    }

    public function getParent()
    {
        return 'entity';
    }
}
