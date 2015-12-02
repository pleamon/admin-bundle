<?php
 
namespace P\AdminBundle\Command;
 
use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCrudCommand;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Sensio\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator;
 
class SensioGeneratorCommand extends GenerateDoctrineCrudCommand
{
    protected $generator;
 
    protected function configure()
    {
        parent::configure();
 
        $this->setName('padmin:generate:crud');
        $this->setDescription('Our admin generator rocks!');
    }

    protected function getGenerator(BundleInterface $bundle = null)
    {
        if (null === $this->generator) {
            $this->generator = $this->createGenerator();
            $this->generator->setSkeletonDirs(array(
                __DIR__.'/../Resources/skeleton/crud'
            ));
        }

        return $this->generator;
    }
}
