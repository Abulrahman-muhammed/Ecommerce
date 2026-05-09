<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NotificationComponent extends Component
{
    public $notifications;
    public $unreadCount;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->notifications = auth()->user()->unreadNotifications->take(5);
        $this->unreadCount = auth()->user()->unreadNotifications->count();    
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.notification-component');
    }
}
