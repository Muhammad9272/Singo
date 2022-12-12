<?php

namespace App\Http\Controllers;

use App\Services\FugaApiService;
use Illuminate\Http\Request;

class Select2SearchController extends Controller
{
    public function searchFugaDSP(Request $request)
    {
        $allDsp[] = [
            'id' => 0,
            'name' => '-- Select DSP --'
        ];

        $client = (new FugaApiService)->getClient();

        $fugaRequest = $client->get('trends/suggestions/dsp', [
            'query' => [
                'text' => $request->term
            ]
        ]);

        $content = $fugaRequest->getBody()->getContents();

        if (!empty($content)) {
            $allDsp = array_merge($allDsp, json_decode($content));
        }

        return $allDsp;
    }
}
