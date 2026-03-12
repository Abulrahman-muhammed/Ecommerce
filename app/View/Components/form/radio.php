<?php

namespace App\View\Components\form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class radio extends Component
{
    public string $name;
    public $oldValue;
    public $statuses;
    /**
     * Create a new component instance.
     */
    public function __construct($name, $oldValue, $statuses)
    {
        $this->name = $name;
        $this->oldValue = $oldValue;
        $this->statuses = $statuses;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.radio', [
            'statuses' => $this->statuses,
            'name' => $this->name,
            'oldValue' => $this->oldValue,
        ]);
    }
}
