<?php

namespace RohanAdhikari\FilamentNepaliDatetime;

use Filament\Infolists\Components\TextEntry;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Filament\Tables\Columns\TextColumn;
use RohanAdhikari\FilamentNepaliDatetime\TextColumn\TextColumnMixin;
use RohanAdhikari\FilamentNepaliDatetime\TextEntry\TextEntryMixin;
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
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command
                    ->askToStarRepoOnGitHub('rohanadhikari/filament-nepali-datetime');
            });

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function bootingPackage()
    {
        TextEntry::mixin(new TextEntryMixin, false);
        TextColumn::mixin(new TextColumnMixin, false);
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
            AlpineComponent::make('filament-nepali-clock-time-picker', __DIR__ . '/../resources/dist/clock-time-picker.js'),
            Css::make('clock-time-picker', __DIR__ . '/../resources/css/clock-time-picker.css'),
        ];
    }
}
