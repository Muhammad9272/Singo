<?php

namespace App\View\Components\Form;

use App\Models\Genre;
use App\Models\Plan;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class SelectPlan extends Component
{
    public $plans = [];

    public $value;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($value = null)
    {
        $this->value = $value;

        $this->plans = Cache::rememberForever('plans', function () {
            return Plan::orderBy('title')->get();
        });
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.select-plan');
    }
}
