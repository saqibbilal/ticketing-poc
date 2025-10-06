<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'body' => 'required|string|max:255',
        ]);

        $ticket = Ticket::findOrFail($validated['ticket_id']);

        if (auth()->user()->isRegular()) {
            abort(403, 'Only Admin and Technician can add comments');
        }

        $comment = Comment::create([
            'ticket_id' => $validated['ticket_id'],
            'author_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'commented',
            'model_type' => 'Comment',
            'model_id' => $comment->id,
            'description' => auth()->user()->name . ' commented on ticket #' . $ticket->id,
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Comment added');
    }
}
