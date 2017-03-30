<?php

/*
 * This file is part of the PAdminBundle package.
 *
 * (c) P Admin <http://padmin.pleamon.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace P\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use P\AdminBundle\DataFixtures\ORM\LoadRegionData;

/**
 * @author Antoine Pleamon <pleamon.li@gmail.com>
 */
class LoadRegionDataCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:load:region')
            ->setDescription('加载地理位置数据')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loader = new LoadRegionData();
        $loader->setContainer($this->getContainer());
        $loader->load($this->getContainer()->get('doctrine.orm.default_entity_manager'));
        $output->writeln("加载完毕!");
    }
}
