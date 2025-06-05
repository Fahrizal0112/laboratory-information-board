<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Monitoring;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monitorings = Monitoring::where('user_id', Auth::id())->latest()->get();
        return view('user.monitorings.index', compact('monitorings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.monitorings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_part' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'no_mol' => 'required|string|max:255',
            'background' => 'required|string|max:255',
        ]);

        Monitoring::create([
            'user_id' => Auth::id(),
            'nama_part' => $request->nama_part,
            'type' => $request->type,
            'no_mol' => $request->no_mol,
            'background' => $request->background,
            'status' => 'pending',
        ]);

        return redirect()->route('user.monitorings.index')
            ->with('success', 'Data monitoring berhasil dibuat dan menunggu persetujuan admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Monitoring $monitoring)
    {
        // Pastikan user hanya bisa melihat data miliknya
        if ($monitoring->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('user.monitorings.show', compact('monitoring'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Monitoring $monitoring)
    {
        // Pastikan user hanya bisa mengedit data miliknya dan yang masih pending
        if ($monitoring->user_id !== Auth::id() || !$monitoring->isPending()) {
            abort(403);
        }
        
        return view('user.monitorings.edit', compact('monitoring'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Monitoring $monitoring)
    {
        // Pastikan user hanya bisa mengupdate data miliknya dan yang masih pending
        if ($monitoring->user_id !== Auth::id() || !$monitoring->isPending()) {
            abort(403);
        }
        
        $request->validate([
            'nama_part' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'no_mol' => 'required|string|max:255',
            'background' => 'required|string|max:255',
        ]);

        $monitoring->update([
            'nama_part' => $request->nama_part,
            'type' => $request->type,
            'no_mol' => $request->no_mol,
            'background' => $request->background,
            'status' => 'pending', // Reset status ke pending jika diedit
        ]);

        return redirect()->route('user.monitorings.index')
            ->with('success', 'Data monitoring berhasil diperbarui dan menunggu persetujuan admin.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Monitoring $monitoring)
    {
        // Pastikan user hanya bisa menghapus data miliknya dan yang masih pending
        if ($monitoring->user_id !== Auth::id() || !$monitoring->isPending()) {
            abort(403);
        }
        
        $monitoring->delete();

        return redirect()->route('user.monitorings.index')
            ->with('success', 'Data monitoring berhasil dihapus.');
    }
}