<?php

namespace App\Command\User;

use App\DTO\User\CreateUserDTO;
use App\UseCase\User\CreateUserAction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class CreateUserCommand extends Command
{
    /**
     * @var string
     * @psalm-suppress NonInvariantDocblockPropertyType
     */
    protected static $defaultName = 'app:create-user';

    /**
     * @var CreateUserAction
     */
    private CreateUserAction $createUserAction;

    /**
     * @param CreateUserAction $createUserAction
     */
    public function __construct(CreateUserAction $createUserAction)
    {
        $this->createUserAction = $createUserAction;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $email = $this->askEmail($helper, $input, $output);
        $password = $this->askPassword($helper, $input, $output);

        $createUserDto = new CreateUserDTO($email, $password);

        $this->createUserAction->execute($createUserDto);

        return Command::SUCCESS;
    }

    /**
     * @param mixed $helper
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    private function askEmail($helper, InputInterface $input, OutputInterface $output)
    {
        $question = new Question('Please enter email:', false);

        $email = $helper->ask($input, $output, $question);

        if (!$email) {
            throw new \InvalidArgumentException('Missing email!');
        }

        return $email;
    }

    /**
     * @param mixed $helper
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    private function askPassword($helper, InputInterface $input, OutputInterface $output)
    {
        $question = new Question('Please enter password:', false);

        $password = $helper->ask($input, $output, $question);

        if (!$password) {
            throw new \InvalidArgumentException('Missing email!');
        }

        return $password;
    }
}