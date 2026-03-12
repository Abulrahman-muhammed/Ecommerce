<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class formInput extends Component
{
    public $label;
    public $inputName;
    public $type;
    public $required;
    public $placeholder;
    /**
     * Create a new component instance.
     */
    public function __construct($label, $inputName, $type, $required = false, $placeholder = '')
    {
        $this->label = $label;
        $this->inputName = $inputName;
        $this->type = $type;
        $this->required = $required;
        $this->placeholder = $placeholder;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        return view('components.form.input', [
            'label' => $this->label,
            'inputName' => $this->inputName,
            'type' => $this->type,
            'required' => $this->required,
            'placeholder' => $this->placeholder,
        ]);
    }
}
