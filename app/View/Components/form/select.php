<?php

namespace App\View\Components\form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class select extends Component
{
    public $label;
    public $inputName;
    public $options ;
    public $inputValue;
    /**
     * Create a new component instance.
     */
    public function __construct($label, $inputName, $options, $inputValue = '')
    {
        $this->label = $label;
        $this->inputName = $inputName;
        $this->options = $options;
        $this->inputValue = $inputValue;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.select', [
            'label' => $this->label,
            'inputName' => $this->inputName,
            'options' => $this->options,
            'inputValue' => $this->inputValue,
        ]);
    }
}
