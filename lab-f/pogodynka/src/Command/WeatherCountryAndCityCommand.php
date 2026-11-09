<?php

namespace App\Command;

use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:country-and-city',
    description: 'Get weather forecast for country and city',
)]
class WeatherCountryAndCityCommand
{
    public function __construct(
        private readonly WeatherUtil $weatherUtil,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country Code')] string $countryCode,
        #[Argument('City Name')] string $cityName,
    ): int {
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($countryCode, $cityName);
        $io->writeln(sprintf('Location: %s', $cityName));
        $io->table(['Date', 'Temperature'], array_map(fn($m) => [
            $m->getDate()->format('Y-m-d'),
            $m->getCelsius(),
        ], $measurements));

        return Command::SUCCESS;
    }
}
