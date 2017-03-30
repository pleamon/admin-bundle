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
class AmqpSubscribeStartCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:amqp:subscribe:start')
            ->setDescription('amqp subscribe run in background process')
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
                $output->writeln('<error>程序正在运行</error>');
                return;
            } else {
                $fs->remove($filename);
            }
        }

        $pid = pcntl_fork();
        if($pid == -1) {
            return;
        } else if($pid) {
        } else {
            $output->writeln(sprintf("<info>start amqp subscribe process: %s</info>", getmypid()));
            file_put_contents($filename, getmypid());
            $amqp->subscribe($exchange, $queue, $routing);
        }
    }
}
