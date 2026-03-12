<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class createButton extends Component
{
    /**
     * Create a new component instance.
     */
    public $route;
    public $label;
    public $routeTrashed;

    public function __construct($route, $label, $routeTrashed = null, $icon = null, $buttonText = null)
    {
        $this->route = $route;
        $this->label = $label;
        $this->routeTrashed = $routeTrashed;
        $this->icon = $icon;
        $this->buttonText = $buttonText;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.create-button', [
            'route' => $this->route,
            'label' => $this->label,
            'routeTrashed' => $this->routeTrashed,
            'icon' => $this->icon,
            'buttonText' => $this->buttonText,
            
        ]);
    }
}
