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
use Symfony\Component\Console\Helper\Table;

/**
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class AmqpBindingsCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:amqp:bindings')
            ->setDescription('amqp bind queue')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $amqp = $this->getContainer()->get('p.amqp');

        $bindings = $amqp->bindings();

        $table = new Table($output);

        $table->setHeaders(array('vhost', 'exchange', 'type', 'queue', 'routing'));
        foreach($bindings as $bind) {
            $table->addRow(array(
                $bind->vhost,
                $bind->source,
                $bind->destination_type,
                $bind->destination,
                $bind->properties_key,
            ));
        }
        $table->render();

    }
}
