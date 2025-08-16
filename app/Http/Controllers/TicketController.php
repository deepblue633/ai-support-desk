<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())->get();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $this->getPriorityFromDescription($request->description),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'priority' => 'nullable|string',
        ]);

        $ticket->update($request->only(['title', 'description', 'status', 'priority']));

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }

    private function getPriorityFromDescription(string $description): string
    {
        $description = strtolower($description);

        $highPriorityKeywords = ['urgent', 'error', 'down', 'critical'];
        $mediumPriorityKeywords = ['slow', 'issue', 'problem'];

        foreach ($highPriorityKeywords as $keyword) {
            if (str_contains($description, $keyword)) {
                return 'High';
            }
        }

        foreach ($mediumPriorityKeywords as $keyword) {
            if (str_contains($description, $keyword)) {
                return 'Medium';
            }
        }

        return 'Low';
    }
}
