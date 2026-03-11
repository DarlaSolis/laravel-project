<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function index()
    {
        // En un caso real podrías usar datatables, pero aquí pasamos los tickets paginados o todos
        $tickets = Ticket::with('user')->latest()->get();
        return view('admin.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('admin.tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $request->user()->tickets()->create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'Abierto',
        ]);

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket enviado exitosamente.');
    }
}
