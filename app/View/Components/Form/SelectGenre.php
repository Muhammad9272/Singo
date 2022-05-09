<?php

namespace App\View\Components\Form;

use App\Models\Genre;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class SelectGenre extends Component
{
    public $genres = [];

    public $value;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($value = null)
    {
        $this->value = $value;

        $this->genres = Cache::rememberForever('genres', function () {
            return Genre::whereNotNull('slug')->orderBy('name')->get();
        });
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.select-genre');
    }
}
