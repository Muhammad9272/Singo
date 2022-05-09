<?php

namespace App\Imports\Processors;

use App\Models\Import;
use Illuminate\View\View;

abstract class BaseProcessor
{
    public $data = [];
    /**
     * @var Import
     */
    public $import;

    /**
     * @param  Import  $import
     */
    public function __construct(Import $import)
    {
        $this->import = $import;
    }

    /**
     * Prepares the data from rendering
     *
     * @return BaseProcessor
     */
    abstract function process(): BaseProcessor;

    /**
     * Renders processed data to view
     * @return View
     */
    abstract function render(): View;

    public function getData()
    {
        return $this->data;
    }
}
