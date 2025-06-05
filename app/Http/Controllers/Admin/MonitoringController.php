<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Monitoring;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monitorings = Monitoring::with('user')->latest()->get();
        return view('admin.monitorings.index', compact('monitorings'));
    }

    /**
     * Display a listing of pending monitorings.
     */
    public function pending()
    {
        $monitorings = Monitoring::with('user')->where('status', 'pending')->latest()->get();
        return view('admin.monitorings.pending', compact('monitorings'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Monitoring $monitoring)
    {
        return view('admin.monitorings.show', compact('monitoring'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Monitoring $monitoring)
    {
        return view('admin.monitorings.edit', compact('monitoring'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Monitoring $monitoring)
    {
        $request->validate([
            'kode_antrian' => 'required|string|max:255',
            'status' => 'required|in:pending,approved,rejected,in_progress,completed',
            'catatan' => 'nullable|string',
        ]);

        $data = [
            'kode_antrian' => $request->kode_antrian,
            'status' => $request->status,
            'catatan' => $request->catatan,
        ];

        // Jika status diubah menjadi in_progress, set waktu mulai
        if ($request->status === 'in_progress' && !$monitoring->start) {
            $data['start'] = now()->setTimezone('Asia/Jakarta');
        }

        // Jika status diubah menjadi completed, set waktu selesai
        if ($request->status === 'completed' && !$monitoring->finish) {
            $data['finish'] = now()->setTimezone('Asia/Jakarta');
        }

        $monitoring->update($data);

        return redirect()->route('admin.monitorings.index')
            ->with('success', 'Data monitoring berhasil diperbarui.');
    }

    /**
     * Approve the monitoring.
     */
    public function approve(Request $request, Monitoring $monitoring)
    {
        $request->validate([
            'kode_antrian' => 'required|string|max:255',
        ]);

        $monitoring->update([
            'status' => 'approved',
            'kode_antrian' => $request->kode_antrian,
            'part_masuk_lab' => now()->setTimezone('Asia/Jakarta')->format('Y-m-d'),
        ]);

        return redirect()->route('admin.monitorings.pending')
            ->with('success', 'Data monitoring berhasil disetujui.');
    }

    /**
     * Reject the monitoring.
     */
    public function reject(Request $request, Monitoring $monitoring)
    {
        $request->validate([
            'catatan' => 'required|string',
        ]);

        $monitoring->update([
            'status' => 'rejected',
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('admin.monitorings.pending')
            ->with('success', 'Data monitoring berhasil ditolak.');
    }
}