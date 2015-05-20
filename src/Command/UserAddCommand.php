<?php

namespace Wanjee\Shuwee\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Wanjee\Shuwee\AdminBundle\Security\UserManager;

/**
 * Class UserAddCommand
 * @package Wanjee\Shuwee\AdminBundle\Command
 */
class UserAddCommand extends ContainerAwareCommand
{
    /**
     * Max number of attempts in interactive mode
     */
    const MAX_ATTEMPTS = 5;

    /**
     * @see Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand::configure()
     */
    protected function configure()
    {
        $this
            ->setName('shuwee:admin:user:add')
            ->setDescription('Create a new user in the database')
            ->setHelp($this->getCommandHelp())
            ->addArgument('username', InputArgument::REQUIRED, 'Username of the new user')
            ->addArgument('password', InputArgument::REQUIRED, 'Plain password of the new user')
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the new user')
            ->addOption('roles', 'r', InputOption::VALUE_OPTIONAL, 'Roles to assign to the new user');
    }

    /**
     * Validates input and ask for missing arguments if any
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $console = $this->getHelper('question');

        $output->writeln(
            array(
                '',
                'This command will create a <info>user</info> for <comment>Shuwee admin</comment>',
                '',
            )
        );

        // Username
        $username = null;
        try {
            $username = $input->getArgument('username') ? $this->usernameValidator(
                $input->getArgument('username')
            ) : null;
        } catch (\Exception $error) {
            $output->writeln($console->getHelperSet()->get('formatter')->formatBlock($error->getMessage(), 'error'));
        }

        if (null === $username) {
            $question = new Question('<info>Username</info>: ');
            $question->setValidator(array($this, 'usernameValidator'));
            $question->setMaxAttempts(self::MAX_ATTEMPTS);
            $username = $console->ask($input, $output, $question);
            $input->setArgument('username', $username);
        } else {
            $output->writeln('<info>Username</info>: '.$username);
        }

        // Password
        $password = null;
        try {
            $password = $input->getArgument('password') ? $this->passwordValidator(
                $input->getArgument('password')
            ) : null;
        } catch (\Exception $error) {
            $output->writeln($console->getHelperSet()->get('formatter')->formatBlock($error->getMessage(), 'error'));
        }

        if (null === $password) {
            $question = new Question('<info>Password</info> (your type will be hidden): ');
            $question->setValidator(array($this, 'passwordValidator'));
            $question->setHidden(true);
            $question->setMaxAttempts(self::MAX_ATTEMPTS);
            $password = $console->ask($input, $output, $question);
            $input->setArgument('password', $password);
        } else {
            $output->writeln('<info>Password</info>: '.str_repeat('*', strlen($password)));
        }

        // Email
        $email = null;
        try {
            $email = $input->getArgument('email') ? $this->emailValidator($input->getArgument('email')) : null;
        } catch (\Exception $error) {
            $output->writeln($console->getHelperSet()->get('formatter')->formatBlock($error->getMessage(), 'error'));
        }

        if (null === $email) {
            $question = new Question('<info>Email</info>: ');
            $question->setValidator(array($this, 'emailValidator'));
            $question->setMaxAttempts(self::MAX_ATTEMPTS);
            $email = $console->ask($input, $output, $question);
            $input->setArgument('email', $email);
        } else {
            $output->writeln('<info>Email</info>: '.$email);
        }

        $output->writeln('');
    }

    /**
     * @see Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $userManager = $this->getUserManager();

        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $roles = $input->getOption('roles');

        $userManager->createUser($username, $email, $password, explode(',', $roles));

        $output->writeln(sprintf('Created user: <info>%s</info>', $username));

        if (!empty($roles)) {
            $output->writeln(sprintf('Assigned roles: <info>%s</info>', $roles));
        } else {
            $output->writeln(sprintf('No role.'));
        }

        if ($output->isVerbose()) {
            $finishTime = microtime(true);
            $elapsedTime = $finishTime - $startTime;
            $output->writeln(sprintf('Elapsed time: <comment>%.2f ms</comment>', $elapsedTime * 1000));
        }
    }

    /**
     * @return UserManager
     */
    private function getUserManager()
    {
        return $this->getContainer()->get('shuwee_admin.security.user_manager');
    }

    /**
     * This internal method should be private, but it's declared as public to
     * maintain PHP 5.3 compatibility when using it in a callback.
     *
     * @internal
     */
    public function usernameValidator($username)
    {
        if (empty($username)) {
            throw new \Exception('The username can not be empty');
        }

        // FIXME Ensure unique

        return $username;
    }

    /**
     * This internal method should be private, but it's declared as public to
     * maintain PHP 5.3 compatibility when using it in a callback.
     *
     * @internal
     */
    public function passwordValidator($plainPassword)
    {
        if (empty($plainPassword)) {
            throw new \Exception('The password can not be empty');
        }

        if (strlen(trim($plainPassword)) < 6) {
            throw new \Exception('The password must be at least 6 characters long');
        }

        return $plainPassword;
    }

    /**
     * This internal method should be private, but it's declared as public to
     * maintain PHP 5.3 compatibility when using it in a callback.
     *
     * @internal
     */
    public function emailValidator($email)
    {
        if (empty($email)) {
            throw new \Exception('The email address can not be empty');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('The email address is considered invalid');
        }

        return $email;
    }

    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     */
    private function getCommandHelp()
    {
        return <<<HELP
The <info>%command.name%</info> command creates a new user in database:

  Usage: <info>php %command.full_name%</info> <comment>username password email</comment>

You can specify user role(s) at creation using <comment>--roles</comment> option:

  Usage: <info>php %command.full_name%</info> <comment>username password email --roles=ROLE_ADMIN,ROLE_SUPER_ADMIN</comment>

If you omit any of the three required arguments, the command will ask you to
provide the missing values:

  # command will ask you for the email
  <info>php %command.full_name%</info> <comment>username password</comment>

  # command will ask you for the email and password
  <info>php %command.full_name%</info> <comment>username</comment>

  # command will ask you for all arguments
  <info>php %command.full_name%</info>

HELP;
    }
}