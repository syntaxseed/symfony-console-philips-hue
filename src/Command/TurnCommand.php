<?php

declare(strict_types=1);

namespace App\Command;

use Phue\Client;
use Phue\Command\GetLightById;
use Phue\Transport\Exception\ResourceUnavailableException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

final class TurnCommand extends Command
{
    private $phueClient;
    public function __construct(Client $phueClient)
    {
        $this->phueClient = $phueClient;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('turn');
        $this->setDescription('Turn on or off the given light ID.');
        $this->addArgument('state', InputArgument::REQUIRED,'State of the light (on/off).', null, ['on', 'off']);
        $this->addArgument('id', InputArgument::REQUIRED, 'ID of the bulb to be turned on.');
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        if (!$input->getArgument('state')) {
            $question = new ChoiceQuestion(
                'Do you want to turn the light on or off?',
                ['on', 'off'],
                null
            );
            $question->setErrorMessage('Invalid choice.');

            $value = $io->askQuestion($question);
            $input->setArgument('state', $value);
        }

        if (!$input->getArgument('id')) {
            $question = new Question(
                'Which light do you want to toggle (ID)?',
                null
            );

            $value = $io->askQuestion($question);
            $input->setArgument('id', $value);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $state = $input->getArgument('state');
        $id = intval($input->getArgument('id'));

        try {
            $light = $this->phueClient->sendCommand(
                new GetLightById($id)
            );

            $light->setOn($state === 'on');

        } catch (ResourceUnavailableException $e) {
            $output->writeln("<error>Error: Light not found.</error>");
            return self::FAILURE;
        } catch (\Throwable $e){
            $output->writeln("<error>Error: Connection or command failure.</error>");
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
