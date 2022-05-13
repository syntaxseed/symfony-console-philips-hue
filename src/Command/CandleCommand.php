<?php

declare(strict_types=1);

namespace App\Command;

use Phue\Client;
use Phue\Command\SetLightState;
use Phue\Transport\Exception\ResourceUnavailableException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CandleCommand extends Command
{
    private $phueClient;
    public function __construct(Client $phueClient)
    {
        $this->phueClient = $phueClient;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('candle');
        $this->setDescription("Flicker the lights like a candle for the given number of seconds.");
        $this->addArgument('seconds', InputArgument::REQUIRED, 'Number of seconds to run the effect.');
        $this->addArgument('id', InputArgument::REQUIRED, 'ID of the bulb to use.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $seconds = intval($input->getArgument('seconds'));
        $id = intval($input->getArgument('id'));

        try {

            $timeStart = microtime(true);
            $timeStop = 0;

            while(($timeStop - $timeStart) < $seconds) {
                // Randomly choose values.
                $brightness = rand(20, 50);
                $colorTemp = rand(420, 450);
                $transitionTime = rand(0, 3) / 10;
                $waitTime = $transitionTime;

                // Setup command.
                $command = new SetLightState($id);
                $command->brightness($brightness)
                    ->colorTemp($colorTemp)
                    ->transitionTime($transitionTime);

                // Send command.
                $this->phueClient->sendCommand($command);

                // Sleep for transition time plus some extra for request length.
                usleep(intval($waitTime * 1000000) + 25000);

                $timeStop = microtime(true);
            }



        } catch (ResourceUnavailableException $e) {
            $output->writeln("<error>Error: Light(s) not found.</error>");
            return self::FAILURE;
        } catch (\Throwable $e) {
            $output->writeln("<error>Error: Connection or command failure.</error>");
            var_dump($e);
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
