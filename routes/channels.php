<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;


Broadcast::routes([
    'middleware' => ['web', 'auth:admin'],
]);

Broadcast::channel('admin', fn ($admin) => $admin !== null);