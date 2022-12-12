<?php

namespace App\View\Components\Form;

use App\Models\AudioLocale;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class SongAudioLocale extends Component
{
    public $name;

    public $selected;

    public $audioLocales;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $selected = 'NA')
    {
        $this->audioLocales = Cache::rememberForever('audioLocales', function () {
            return AudioLocale::where('name', '!=', 'Instrumental')
                ->orderBy('name')
                ->get();
        });
        $this->name = $name;
        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.song-audio-locale');
    }
}
