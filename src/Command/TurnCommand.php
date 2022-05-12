<?php

declare(strict_types=1);

namespace App\Command;

use Phue\Client;
use Phue\Command\GetLightById;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Phue\Transport\Exception\ResourceUnavailableException;

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
        $this->setDescription("Turn on or off the given light ID.\nExample: <comment>huelight turn on 1</comment>");
        $this->addArgument('state', InputArgument::REQUIRED, 'State of the light (on/off).');
        $this->addArgument('id', InputArgument::REQUIRED, 'ID of the light to be turned on.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $state = $input->getArgument('state');
        $id = $input->getArgument('id');

        try {
            $light = $this->phueClient->sendCommand(
                new GetLightById(intval($id))
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
