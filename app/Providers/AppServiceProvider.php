<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Policies\TicketPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Gate::policy(Ticket::class, TicketPolicy::class);
    }
}
