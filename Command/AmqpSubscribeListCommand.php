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
use Symfony\Component\Console\Helper\Table;

/**
 * @author Antoine Pleamon <pleamon.li@gmail.com>
 */
class AmqpSubscribeListCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:amqp:subscribe:list')
            ->setDescription('amqp stop subscribe background process')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $amqp = $container->get('p.amqp');

        $cacheDir = $container->getParameter('kernel.cache_dir') . '/amqp';
        $fs = new Filesystem();
        try{
            $fs->mkdir($cacheDir);
        } catch (IOException $e) {
            echo "An error occured while creating your directory";
        }

        $files = $fs->scandir($cacheDir);

        if(count($files) == 0) {
            $output->write("<info>没有正在运行的程序.</info>");
            return;
        }

        $table = new Table($output);
        $table->setHeaders(array('pid', 'exchange', 'queue', 'routing', 'status'));
        foreach($files as $file) {
            $pid = file_get_contents($cacheDir . '/' . $file);
            if($pid && !posix_getpgid($pid)) {
                $fs->remove($cacheDir . '/' . $file);
                continue;
            }
            list($exchange, $queue, $routing, $_) = explode('.', $file);
            $table->addRow(array(
                $pid,
                $exchange,
                $queue,
                $routing,
                'running',
            ));
        }
        $table->render();
    }
}
