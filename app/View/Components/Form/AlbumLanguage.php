<?php

namespace App\View\Components\Form;

use App\Models\Language;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class AlbumLanguage extends Component
{
    public $name;

    public $selected;

    public $languages;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $selected = -1)
    {
        $this->languages = Cache::rememberForever('languages', function () {
            return Language::orderBy('name')->get();
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
        return view('components.form.album-language');
    }
}
