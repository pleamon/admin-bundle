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

use P\AdminBundle\Component\Ctontab\Event\CrontabEvent;

/**
 * @author Antoine Pleamon <pleamon.li@gmail.com>
 */
class CrontabRunCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:crontab:run')
            ->setDescription('crontab output to console')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $schedules = $this->getContainer()->get('p.crontab')->getSchedules();

        foreach($schedules as $schedule) {
            $timer = $schedule->getSchedule();
            echo $timer, PHP_EOL;
        }
    }
}
