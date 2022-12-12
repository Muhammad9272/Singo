<?php

namespace App\Providers;

use App\Charts\ArtistDownloadsChart;
use App\Charts\ArtistStreamsChart;
use Illuminate\Support\ServiceProvider;
use ConsoleTVs\Charts\Registrar as Charts;

class ChartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Charts $charts)
    {
        $charts->register([
            ArtistStreamsChart::class,
            ArtistDownloadsChart::class,
        ]);
    }
}
