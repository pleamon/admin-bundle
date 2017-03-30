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
class AmqpSubscribeKillCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:amqp:subscribe:kill')
            ->setDescription('amqp kill subscribe pid')
            ->addArgument('pid', InputArgument::REQUIRED, 'pid')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $amqp = $container->get('p.amqp');

        $pid = $input->getArgument('pid');;

        $cacheDir = $container->getParameter('kernel.cache_dir') . '/amqp';
        $fs = new Filesystem();
        try{
            $fs->mkdir($cacheDir);
        } catch (IOException $e) {
            echo "An error occured while creating your directory";
        }

        if(posix_getpgid($pid)) {
            if(posix_kill($pid, 3)) {

                $files = $fs->scandir($cacheDir);
                foreach($files as $file) {
                    list($exchange, $queue, $routing, $_) = explode('.', $file);
                    $_pid = file_get_contents($cacheDir . '/' . $file);
                    if($pid == $_pid) {
                        $filename = sprintf("%s/%s.%s.%s.pid", $cacheDir, $exchange, $queue, $routing);
                        $fs->remove($filename);
                        break;
                    }
                }
                $output->writeln(sprintf("<info>已关闭程序, pid: %s, exchange: %s, queue: %s, routing: %s</info>", $pid, $exchange, $queue, $routing));

            } else {
                $output->writeln('<error>关闭失败</error>');
            }
            return;
        } else {
            $output->writeln('<comment>程序没有运行</comment>');
        }
    }
}
