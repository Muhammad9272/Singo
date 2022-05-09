<?php

declare(strict_types=1);

namespace App\Charts;

use App\Models\User;
use App\Services\FugaApiService;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class ArtistDownloadsChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $labels = [];

        $chart = Chartisan::build();

        if ($request->user_id) {
            $artist_id = User::find($request->user_id)->fuga_artist_id;
        } else {
            $artist_id = auth()->user()->fuga_artist_id;
        }

        $fugaApiService = (new FugaApiService());

        if (empty($artist_id)) {
            $response = $fugaApiService->searchArtist(auth()->user()->artistName);

            if (!is_array($response)) {
                return $chart->labels($labels)->dataset('No Data', []);
            }

            if (count($response) === 1) {
                auth()->user()->update([
                    'fuga_artist_id' => $response[0]['id']
                ]);
                $artist_id = $response[0]['id'];
            }
        }

        $client = $fugaApiService->getClient();

        $queryParams = [
            'artist_id' => $artist_id,
            'sale_type' => 'download',
            'selection_type' => 'dsp',
        ];

        $request_date_range = $request->date_range ?: now()->subDays(7)->format('Y-m-d') . ' - ' . now()->format('Y-m-d');

        $dates = explode(' - ', $request_date_range);
        $dateRange = CarbonPeriod::create($dates[0], $dates[1]);

        foreach ($dateRange as $date) {
            $labels[] = "{$date->format('d M')}";
        }

        $queryParams['start_date'] = $dates[0];
        $queryParams['end_date'] = $dates[1];

        if ($request->has('dsp_id') && $request->get('dsp_id') != 0) {
            $queryParams['dsp_id'] = $request->get('dsp_id');
        }

        $fugaRequest = $client->get('trends/aggregated-dsp-statistic', [
            'query' => $queryParams
        ]);

        $response = json_decode($fugaRequest->getBody()->getContents(), true);

        $total = 0;
        if (count($response['chart']['data'])) {
            foreach ($response['chart']['data'] as $dsp) {
                $chart = $chart->labels($labels)->dataset($dsp['name'], $dsp['totals']);
                $total += array_sum($dsp['totals']);
            }
        }

        $chart->advancedDataset('total', [$total], []);

        return $chart;
    }
}
