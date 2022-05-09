<?php

namespace App\View\Components\Home;

use Illuminate\View\Component;

class StatCard extends Component
{
    /*
     * @var string
     */
    public $value;

    /*
     * @var string
     */
    public $label;

    /*
     * @var string
     */
    public $icon;

    /*
     * @var string
     */
    public $variant;



    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($value, $label, $icon = null, $variant = 'primary')
    {
        $this->value = $value;
        $this->label = $label;
        $this->icon = $icon;
        $this->variant = $variant;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.stat-card');
    }
}
