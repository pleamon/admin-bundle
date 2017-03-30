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
use P\AdminBundle\DataFixtures\ORM\LoadUserRoleData;

/**
 * @author Antoine Pleamon <pleamon.li@gmail.com>
 */
class LoadRoleCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:load:user:role')
            ->setDescription('加载用户权限数据')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loader = new LoadUserRoleData();
        $loader->setContainer($this->getContainer());
        $loader->load($this->getContainer()->get('doctrine.orm.default_entity_manager'));
        $output->writeln("加载完毕!");
    }
}
