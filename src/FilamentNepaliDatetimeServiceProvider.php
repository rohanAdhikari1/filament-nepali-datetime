<?php

namespace RohanAdhikari\FilamentNepaliDatetime;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use RohanAdhikari\FilamentNepaliDatetime\Concerns\CanFormat;
use RohanAdhikari\FilamentNepaliDatetime\Concerns\HaveMacro;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentNepaliDatetimeServiceProvider extends PackageServiceProvider
{
    use CanFormat;
    use HaveMacro;

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
        $this->registerTextColumnMacros();
        $this->registerTextEntryMacros();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );
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
            AlpineComponent::make('filament-nepali-datetime-picker', __DIR__ . '/../resources/dist/filament-nepali-datetime.js'),
        ];
    }
}
