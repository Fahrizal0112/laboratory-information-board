<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RunningText;
use Illuminate\Http\Request;

class RunningTextController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $runningTexts = RunningText::orderBy('order')->get();
        return view('admin.running-texts.index', compact('runningTexts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.running-texts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'active' => 'boolean',
        ]);

        // Mendapatkan order tertinggi
        $maxOrder = RunningText::max('order') ?? 0;

        RunningText::create([
            'text' => $request->text,
            'active' => $request->has('active'),
            'order' => $maxOrder + 1,
        ]);

        return redirect()->route('admin.running-texts.index')
            ->with('success', 'Running text berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RunningText $runningText)
    {
        return view('admin.running-texts.edit', compact('runningText'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RunningText $runningText)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'active' => 'boolean',
        ]);

        $runningText->update([
            'text' => $request->text,
            'active' => $request->has('active'),
        ]);

        return redirect()->route('admin.running-texts.index')
            ->with('success', 'Running text berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RunningText $runningText)
    {
        $runningText->delete();

        return redirect()->route('admin.running-texts.index')
            ->with('success', 'Running text berhasil dihapus.');
    }

    /**
     * Reorder running texts
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:running_texts,id',
        ]);

        foreach ($request->order as $index => $id) {
            RunningText::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
} 