<?php

namespace RohanAdhikari\FilamentNepaliDatetime;

use Carbon\Carbon;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Tables\Columns\TextColumn;
use RohanAdhikari\FilamentNepaliDatetime\Commands\FilamentNepaliDatetimeCommand;
use RohanAdhikari\FilamentNepaliDatetime\Services\NepaliDate;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentNepaliDatetimeServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-nepali-datetime';

    public static string $viewNamespace = 'filament-nepali-datetime';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('rohanadhikari/filament-nepali-datetime');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function bootingPackage()
    {
        TextColumn::macro('nepaliDate', function (string $format = 'Y-m-d', string $locale = 'en', ?string $timezone = null) {
            /** @var TextColumn $this */
            $this->formatStateUsing(static function (TextColumn $column, $state) use ($format, $timezone, $locale): ?string {
                if (blank($state)) {
                    return null;
                }
                return NepaliDate::fromAd(Carbon::parse($state)
                    ->setTimezone($timezone ?? $column->getTimezone()))
                    ->locale($locale)
                    ->format($format);
            });
            return $this;
        });
        TextEntry::macro('nepaliDate', function (string $format = 'Y-m-d', string $locale = 'en', ?string $timezone = null) {
            /** @var TextEntry $this */
            $this->formatStateUsing(static function (TextEntry $component, $state) use ($format, $timezone, $locale): ?string {
                if (blank($state)) {
                    return null;
                }
                return NepaliDate::fromAd(Carbon::parse($state)
                    ->setTimezone($timezone ?? $component->getTimezone()))
                    ->locale($locale)
                    ->format($format);
            });
            return $this;
        });
    }

    public function packageBooted(): void
    {
        // Asset Registration
        // FilamentAsset::register(
        //     $this->getAssets(),
        //     $this->getAssetPackageName()
        // );
    }

    protected function getAssetPackageName(): ?string
    {
        return 'rohanadhikari/filament-nepali-datetime';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-nepali-datetime', __DIR__ . '/../resources/dist/components/filament-nepali-datetime.js'),
            // Css::make('filament-nepali-datetime-styles', __DIR__ . '/../resources/dist/filament-nepali-datetime.css'),
            // Js::make('filament-nepali-datetime-scripts', __DIR__ . '/../resources/dist/filament-nepali-datetime.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentNepaliDatetimeCommand::class,
        ];
    }
}
