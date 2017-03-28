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
class AmqpPublishCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:amqp:publish')
            ->setDescription('amqp publish message')
            ->addArgument('exchange', InputArgument::REQUIRED, 'exchange')
            ->addArgument('queue', InputArgument::REQUIRED, 'queue')
            ->addArgument('routing', InputArgument::REQUIRED, 'routing')
            ->addArgument('message', InputArgument::REQUIRED, 'message')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $amqp = $this->getContainer()->get('p.amqp');
        $exchange = $input->getArgument('exchange');;
        $queue = $input->getArgument('queue');;
        $routing = $input->getArgument('routing');;
        $message = $input->getArgument('message');

        //$result = $amqp->publish('test', 'test', '/', $body);
        $result = $amqp->publish($exchange, $queue, $routing, $message);
        $output->writeln($result);
    }
}
