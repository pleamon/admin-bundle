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
use P\AdminBundle\DataFixtures\ORM\LoadIconData;

/**
 * @author Antoine Pleamon <pleamon.li@gmail.com>
 */
class AmqpBindCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:amqp:bind')
            ->setDescription('amqp bind')
            ->addArgument('exchange', InputArgument::REQUIRED, 'exchange name')
            ->addArgument('queue', InputArgument::REQUIRED, 'queue name')
            ->addArgument('routing', InputArgument::REQUIRED, 'routing name')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $amqp = $this->getContainer()->get('p.amqp');
        $exchange = $input->getArgument('exchange');;
        $queue = $input->getArgument('queue');;
        $routing = $input->getArgument('routing');;

        $amqp->bind($exchange, $queue, $routing);
    }
}
