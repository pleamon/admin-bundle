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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @author Antoine Pleamon <pleamon.li@gmail.com>
 */
class UserCreateCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('p:user:create')
            ->setDescription('创建用户')
            ->setDefinition(array(
                new InputArgument('username', InputArgument::REQUIRED, '用户名'),
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('password', InputArgument::REQUIRED, '密码'),
                new InputOption('super-admin', null, InputOption::VALUE_NONE, '设置为超级管理员'),
                new InputOption('inactive', null, InputOption::VALUE_NONE, '设置为禁止登陆状态'),
            ))
            ->setHelp(<<<'EOT'
创建用户

<info>php %command.full_name% pleamon</info>

非交互模式创建用户

<info>php %command.fuul_name% pleamon pleamon.li@gmail.com password</info>

创建超级管理员

<info>php %command.fuul_name% --super-admin</info>

创建禁止登陆用户

<info>php %command.fuul_name% --inactive</info>

EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $inactive = $input->getOption('inactive');
        $superadmin = $input->getOption('super-admin');
        $container = $this->getContainer();

        $manipulator = $container->get('fos_user.util.user_manipulator');
        $user = $manipulator->create($username, $password, $email, !$inactive, false);

        $output->writeln(sprintf('创建用户成功 <comment>%s</comment>', $username));
        if($superadmin) {
            $em = $container->get('doctrine.orm.default_entity_manager');
            $role = 'ROLE_SUPERADMIN';
            $group = $em->getRepository('PUserBundle:Group')->createQueryBuilder('g')
                ->join('g.roles', 'r')
                ->where('r.name = :role')
                ->setParameter('role', $role)
                ->getQuery()
                ->setMaxResults(1)
                ->getOneOrNullResult()
                ;

            if($group) {
                $user->addGroup($group);
                $em->persist($user);
                $em->flush();
                $output->writeln(sprintf('分配超级管理员权限 <comment>%s</comment><info>%s</info>', $username, $group->getName()));
            } else {
                $output->writeln('<error>没有找到拥有%s权限的用户组</error>', $role);
            }
        }
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('username')) {
            $question = new Question('请输入用户名:');
            $question->setValidator(function ($username) {
                if (empty($username)) {
                    throw new \Exception('用户名不能为空');
                }

                return $username;
            });
            $questions['username'] = $question;
        }

        if (!$input->getArgument('email')) {
            $question = new Question('请输入 email:');
            $question->setValidator(function ($email) {
                if (empty($email)) {
                    throw new \Exception('Email 不能为空');
                }

                return $email;
            });
            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('请输入密码:');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('密码不能为空');
                }

                return $password;
            });
            $question->setHidden(true);
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}

