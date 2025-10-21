<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kbm;
use App\Models\guru;
use App\Models\Walas;

class kbmController extends Controller
{
    /**
     * Mengambil semua data jadwal KBM
     * Admin: Lihat semua jadwal
     * Guru: Lihat jadwal mengajar sendiri
     * Siswa: Lihat jadwal kelas sendiri
     */
    public function index()
    {
        $role = session('admin_role');
        $adminId = session('admin_id');

        $guru = null;
        $siswaData = null;
        $kelasData = null;

        if ($role === 'guru') {
            $guru = guru::where('id', $adminId)->first();
            
            if (!$guru) {
                return redirect()->route('home')->with('error', 'Data guru tidak ditemukan.');
            }
        } elseif ($role === 'siswa') {
            $siswaData = \App\Models\Siswa::where('id', $adminId)
                ->with(['kelas.walas'])
                ->first();
            
            if (!$siswaData || !$siswaData->kelas) {
                return redirect()->route('home')->with('error', 'Anda belum terdaftar di kelas manapun.');
            }
            
            $kelasData = $siswaData->kelas->walas;
        }

        return view('kbm.index', compact('guru', 'siswaData', 'kelasData'));
    }

    public function getData(Request $request)
    {
        $role = session('admin_role');
        $adminId = session('admin_id');
        $hari = $request->input('hari');

        $query = kbm::with(['guru', 'walas']);

        if ($role === 'admin') {
            // Admin melihat semua jadwal
            if ($hari) {
                $query->where('hari', $hari);
            }
        } elseif ($role === 'guru') {
            // Guru hanya melihat jadwal mengajar sendiri
            $guru = guru::where('id', $adminId)->first();
            
            if (!$guru) {
                return response()->json(['error' => 'Data guru tidak ditemukan'], 404);
            }
            
            $query->where('idguru', $guru->idguru);
            
            if ($hari) {
                $query->where('hari', $hari);
            }
        } elseif ($role === 'siswa') {
            // Siswa melihat jadwal kelas sendiri
            $siswaData = \App\Models\Siswa::where('id', $adminId)
                ->with(['kelas.walas'])
                ->first();
            
            if (!$siswaData || !$siswaData->kelas) {
                return response()->json(['error' => 'Anda belum terdaftar di kelas manapun'], 404);
            }
            
            $query->where('idwalas', $siswaData->kelas->idwalas);
            
            if ($hari) {
                $query->where('hari', $hari);
            }
        } else {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        $jadwals = $query->get();
        return response()->json($jadwals);
    }

    /**
     * Mengambil data jadwal KBM berdasarkan guru tertentu
     * Hanya admin yang bisa akses
     */
    public function showByGuru($idguru)
    {
        $role = session('admin_role');

        if ($role !== 'admin') {
            return redirect()->route('kbm.index')->with('error', 'Akses ditolak. Hanya admin yang dapat melihat jadwal guru lain.');
        }

        $guru = guru::with(['kbm.walas'])->findOrFail($idguru);
        return view('kbm.by-guru', compact('guru'));
    }

    /**
     * Mengambil data jadwal KBM berdasarkan kelas tertentu
     * Admin dan Guru bisa akses
     */
    public function showByKelas($idwalas)
    {
        $role = session('admin_role');

        $kelas = Walas::with(['kbm.guru'])->findOrFail($idwalas);
        return view('kbm.by-kelas', compact('kelas'));
    }
}
