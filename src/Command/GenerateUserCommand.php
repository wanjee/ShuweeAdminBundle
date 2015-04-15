<?php

namespace Wanjee\Shuwee\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wanjee\Shuwee\AdminBundle\Security\UserManager;

/**
 * Class GenerateUserCommand
 * @package Wanjee\Shuwee\AdminBundle\Command
 */
class GenerateUserCommand extends ContainerAwareCommand
{
    /**
     * @see Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand::configure()
     */
    protected function configure()
    {
        $this
            ->setName('shuwee:admin:generate:user')
            ->setDescription('Create a new user in the database')
            ->setDefinition(array(
                new InputArgument('username', InputArgument::REQUIRED),
                new InputArgument('email', InputArgument::REQUIRED),
                new InputArgument('password', InputArgument::REQUIRED),
                new InputOption('roles', 'r', InputOption::VALUE_OPTIONAL)
            ))
            ->setHelp(<<<EOT
The <info>shuwee:admin:generate:user</info> command creates a user:
  <info>php bin/console shuwee:admin:generate:user username email password</info>
You can create a super admin via the roles flag:
  <info>php bin/console shuwee:admin:generate:user username email password --roles=ADMIN,SUPER_ADMIN</info>
EOT
            );
    }

    /**
     * @see Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userManager = $this->getUserManager();

        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $roles = $input->getOption('roles');

        $userManager->createUser($username, $email, $password, explode(',', $roles));

        $output->writeln(sprintf('Created user <comment>%s</comment>', $username));
    }

    /**
     * @return UserManager
     */
    private function getUserManager()
    {
        return $this->getContainer()->get('shuwee_admin.security.user_manager');
    }
}