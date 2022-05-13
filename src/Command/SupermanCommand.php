<?php

declare(strict_types=1);

namespace App\Command;

use Phue\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Phue\Transport\Exception\ResourceUnavailableException;

final class SupermanCommand extends Command
{
    private $phueClient;
    public function __construct(Client $phueClient)
    {
        $this->phueClient = $phueClient;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('superman');
        $this->setDescription("Set the living room lights to red and blue.");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        try {
            $lights = $this->phueClient->getLights();

            $lights[1]->setRGB(255, 0, 0); // Red
            $lights[2]->setRGB(0, 0, 255); // Blue

            $lights[1]->setBrightness(255);
            $lights[2]->setBrightness(255);

            $lights[1]->setOn(true);
            $lights[2]->setOn(true);

        } catch (ResourceUnavailableException $e) {
            $output->writeln("<error>Error: Light(s) not found.</error>");
            return self::FAILURE;
        } catch (\Throwable $e) {
            $output->writeln("<error>Error: Connection or command failure.</error>");
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
