<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\Request;

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

        Comment::create([
            'ticket_id' => $validated['ticket_id'],
            'author_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Comment added');
    }
}
