<?php

namespace App\Http\Controllers;

use App\Models\PenerimaFinal;
use Illuminate\Http\Request;

class PenerimaFinalController extends Controller
{
    public function index()
    {
        $penerimaFinals = PenerimaFinal::with(['calonPenerima.rt.dusun'])
            ->orderBy('tanggal_penetapan', 'desc')
            ->paginate(20);

        return view('admin.penerima-final.index', compact('penerimaFinals'));
    }

    public function show(PenerimaFinal $penerimaFinal)
    {
        $penerimaFinal->load(['calonPenerima.rt.dusun', 'calonPenerima.prediksiKelayakan']);
        
        return view('admin.penerima-final.show', compact('penerimaFinal'));
    }

    public function updatePencairan(Request $request, PenerimaFinal $penerimaFinal)
    {
        $validated = $request->validate([
            'status_pencairan' => 'required|in:belum_cair,sudah_cair',
            'tanggal_pencairan' => 'required_if:status_pencairan,sudah_cair|nullable|date',
        ]);

        $penerimaFinal->update($validated);

        return redirect()->route('penerima-final.index')
            ->with('success', 'Status pencairan berhasil diupdate!');
    }

    public function export()
    {
        // TODO: Implement export to Excel/PDF
        return redirect()->back()
            ->with('info', 'Fitur export sedang dalam pengembangan');
    }
}