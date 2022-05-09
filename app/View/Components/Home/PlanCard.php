<?php

namespace App\View\Components\Home;

use App\Models\Plan;
use Illuminate\View\Component;

class PlanCard extends Component
{
    /**
     * @var Plan
     */
    public $plan;

    public $high_price = 0;

    public $check = 0;

    public $total_amount = 0;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Plan $plan)
    {
        $this->plan = $plan;

        if($this->high_price < $plan->total_amount)
        {
            $this->high_price = $plan->total_amount;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.plan-card');
    }
}
