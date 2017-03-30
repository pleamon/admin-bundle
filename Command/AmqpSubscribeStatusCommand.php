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
use P\AdminBundle\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

/**
 * @author Antoine Pleamon <pleamon.li@gmail.com>
 */
class AmqpSubscribeStatusCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:amqp:subscribe:status')
            ->setDescription('amqp subscribe')
            ->addArgument('exchange', InputArgument::REQUIRED, 'exchange')
            ->addArgument('queue', InputArgument::REQUIRED, 'queue')
            ->addArgument('routing', InputArgument::REQUIRED, 'routing')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $amqp = $container->get('p.amqp');
        $exchange = $input->getArgument('exchange');;
        $queue = $input->getArgument('queue');;
        $routing = $input->getArgument('routing');;

        $cacheDir = $container->getParameter('kernel.cache_dir') . '/amqp';
        $fs = new Filesystem();
        try{
            $fs->mkdir($cacheDir);
        } catch (IOException $e) {
            echo "An error occured while creating your directory";
        }

        $filename = sprintf("%s/%s.%s.%s.pid", $cacheDir, $exchange, $queue, $routing);

        $pid = null;
        if($fs->exists($filename)) {
            $pid = file_get_contents($filename);
            if(posix_getpgid($pid)) {
                $output->writeln(sprintf("<info>正在运行, pid: %s, exchange: %s, queue: %s, routing: %s</info>", $pid, $exchange, $queue, $routing));
                return;
            }
        }
        $output->writeln('<comment>程序没有运行</comment>');

    }
}
