<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
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
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class AmqpQueueDeclareCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:amqp:queue:declare')
            ->setDescription('amqp declare queue')
            ->addArgument('queue', InputArgument::REQUIRED, 'queue name')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $amqp = $this->getContainer()->get('p.amqp');
        $queueName = $input->getArgument('queue');;

        $amqp->declareQueue($queueName);
    }
}
