<?php
namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\ActivityLog;

class TicketController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('viewAny', Ticket::class);

        $tickets = Ticket::with(['affectedUser', 'assignedTo', 'comments'])
            ->withTrashed()
            ->latest()
            ->get();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $this->authorize('create', Ticket::class);

        $users = User::orderBy('name')->get();
        $technicians = User::whereHas('role', fn($q) => $q->where('name', 'Technician'))->get();
        return view('tickets.create', compact('users', 'technicians'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Ticket::class);

        $validated = $request->validate([
            'affected_user_id' => 'required|exists:users,id',
            'problem_description' => 'required|string',
            'received_date' => 'required|date|before_or_equal:today',
            'additional_notes' => 'nullable|string|max:128',
            'assigned_to_id' => 'nullable|exists:users,id',
        ]);

        $ticket = Ticket::create($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'model_type' => 'Ticket',
            'model_id' => $ticket->id,
            'description' => auth()->user()->name . ' created ticket #' . $ticket->id,
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket created');
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $ticket->load(['affectedUser', 'assignedTo', 'comments.author']);
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $users = User::orderBy('name')->get();
        $technicians = User::whereHas('role', fn($q) => $q->where('name', 'Technician'))->get();
        return view('tickets.edit', compact('ticket', 'users', 'technicians'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $validated = $request->validate([
            'affected_user_id' => 'required|exists:users,id',
            'problem_description' => 'required|string',
            'received_date' => 'required|date|before_or_equal:today',
            'additional_notes' => 'nullable|string|max:128',
            'assigned_to_id' => 'nullable|exists:users,id',
        ]);

        $ticket->update($validated);
        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket updated');
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);

        $ticket->delete();

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'model_type' => 'Ticket',
            'model_id' => $ticket->id,
            'description' => auth()->user()->name . ' deleted ticket #' . $ticket->id,
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted');
    }

    public function restore($id)
    {
        $ticket = Ticket::withTrashed()->findOrFail($id);
        $this->authorize('restore', $ticket);

        $ticket->restore();

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'restored',
            'model_type' => 'Ticket',
            'model_id' => $ticket->id,
            'description' => auth()->user()->name . ' restored ticket #' . $ticket->id,
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket restored');
    }

    public function assignToSelf(Ticket $ticket)
    {
        if (!auth()->user()->isTechnician()) {
            abort(403, 'Only technicians can assign tickets to themselves');
        }

        $ticket->update(['assigned_to_id' => auth()->id()]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'assigned',
            'model_type' => 'Ticket',
            'model_id' => $ticket->id,
            'description' => auth()->user()->name . ' assigned ticket #' . $ticket->id . ' to themselves',
        ]);

        return back()->with('success', 'Ticket assigned to you');
    }
}
