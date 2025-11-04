<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kbm;
use App\Models\guru;
use App\Models\Walas;
use App\Services\KbmService;

class kbmController extends Controller
{
    protected $kbmService;

    public function __construct(KbmService $kbmService)
    {
        $this->kbmService = $kbmService;
    }
    /**
     * Mengambil semua data jadwal KBM
     * Admin: Lihat semua jadwal
     * Guru: Lihat jadwal mengajar sendiri
     * Siswa: Lihat jadwal kelas sendiri
     */
    public function index()
    {
        try {
            $data = $this->kbmService->getIndexData();
            return view('kbm.index', $data);
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', $e->getMessage());
        }
    }

    public function getData(Request $request)
    {
        try {
            $hari = $request->input('hari');
            $jadwals = $this->kbmService->getKbmData($hari);
            return response()->json($jadwals);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Mengambil data jadwal KBM berdasarkan guru tertentu
     * Hanya admin yang bisa akses
     */
    public function showByGuru($idguru)
    {
        try {
            $guru = $this->kbmService->getKbmByGuru($idguru);
            return view('kbm.by-guru', compact('guru'));
        } catch (\Exception $e) {
            return redirect()->route('kbm.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Mengambil data jadwal KBM berdasarkan kelas tertentu
     * Admin dan Guru bisa akses
     */
    public function showByKelas($idwalas)
    {
        $kelas = $this->kbmService->getKbmByKelas($idwalas);
        return view('kbm.by-kelas', compact('kelas'));
    }
}
