<?php
namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user)
    {
        // All authenticated users can view ticket list
        return true;
    }

    public function view(User $user, Ticket $ticket)
    {
        // All authenticated users can view individual tickets
        return true;
    }

    public function create(User $user)
    {
        // Only Admin and Technician can create tickets
        return $user->isAdmin() || $user->isTechnician();
    }

    public function update(User $user, Ticket $ticket)
    {
        // Only Admin and Technician can update tickets
        return $user->isAdmin() || $user->isTechnician();
    }

    public function delete(User $user, Ticket $ticket)
    {
        // Only Admin and Technician can delete tickets
        return $user->isAdmin() || $user->isTechnician();
    }

    public function restore(User $user, Ticket $ticket)
    {
        // Only Admin and Technician can restore tickets
        return $user->isAdmin() || $user->isTechnician();
    }
}
