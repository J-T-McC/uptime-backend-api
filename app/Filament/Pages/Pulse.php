<?php

namespace App\Filament\Pages;

use Dotswan\FilamentLaravelPulse\Widgets\PulseCache;
use Dotswan\FilamentLaravelPulse\Widgets\PulseExceptions;
use Dotswan\FilamentLaravelPulse\Widgets\PulseQueues;
use Dotswan\FilamentLaravelPulse\Widgets\PulseServers;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowOutGoingRequests;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowQueries;
use Dotswan\FilamentLaravelPulse\Widgets\PulseSlowRequests;
use Dotswan\FilamentLaravelPulse\Widgets\PulseUsage;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Pages\Dashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Facades\Gate;

class Pulse extends Dashboard
{
    use HasFiltersAction;

    protected static ?int $navigationSort = -1;

    protected static string $routePath = '/pulse';

    protected static string $view = 'vendor.pulse.dashboard';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Laravel Pulse';

    protected static ?string $title = 'Laravel Pulse';

    public function getColumns(): int|string|array
    {
        return 12;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Statistics';
    }

    public static function canAccess(): bool
    {
        return Gate::allows('viewPulse');
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('1h')
                    ->action(fn () => $this->redirect(route('filament.admin.pages.pulse'))),
                Action::make('24h')
                    ->action(fn () => $this->redirect(route('filament.admin.pages.pulse', ['period' => '24_hours']))),
                Action::make('7d')
                    ->action(fn () => $this->redirect(route('filament.admin.pages.pulse', ['period' => '7_days']))),
            ])
                ->label(__('Filter'))
                ->icon('heroicon-m-funnel')
                ->size(ActionSize::Small)
                ->color('gray')
                ->button(),
        ];
    }

    public function getWidgets(): array
    {
        return [
            PulseServers::class,
            PulseCache::class,
            PulseExceptions::class,
            PulseUsage::class,
            PulseQueues::class,
            PulseSlowQueries::class,
            PulseSlowRequests::class,
            PulseSlowOutGoingRequests::class,
        ];
    }
}
