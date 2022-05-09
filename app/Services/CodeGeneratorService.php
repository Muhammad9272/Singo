<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Song;

class CodeGeneratorService
{
    public function generateISRC(): string
    {
        // Real ISRC - DE1CV2100003

        $all_isrc = Setting::where('name', 'temp_isrc_codes')
            ->first();

        $all_isrc = collect($all_isrc ? json_decode($all_isrc->value, JSON_OBJECT_AS_ARRAY): []);

        $existing_isrc = Song::whereNotNull('isrc')
            ->select('isrc')
            ->pluck('isrc');

        return $all_isrc->diff($existing_isrc)->random();
    }

    public function generateCatalogNumber()
    {

    }
}
