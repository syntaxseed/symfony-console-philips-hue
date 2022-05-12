<?php

declare(strict_types=1);

namespace App\Command;

//use App\Service\PhueService;
use Phue\Client;
use Phue\Command\GetLightById;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GetLightsCommand extends Command
{
    private $phueClient;
    public function __construct(Client $phueClient){

        $this->phueClient = $phueClient;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('get-lights');
        $this->setDescription('Get the list of available lights from the bridge.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $lights = $this->phueClient->getLights();

        foreach ($lights as $lightId => $light) {
            $output->writeln("<info>Id #{$lightId} - {$light->getName()}</info>");
        }

        return self::SUCCESS;  // In case of error use: return self::FAILURE;
    }
}
