<?php

namespace App\View\Components\Form;

use Illuminate\View\Component;

class Select2Dsp extends Component
{
    public $allDsp = [];

    public $name;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->allDsp = collect(config('fuga.stream_trends_dsp'))
            ->sortBy('name')
            ->toArray();

        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.select2-dsp');
        
    }
}
