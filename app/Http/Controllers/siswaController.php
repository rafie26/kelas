<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\siswa;
use App\Models\admin;
use App\Services\SiswaService;
use App\Http\Requests\StoreSiswaRequest;

class siswaController extends Controller
{
    protected $service;
    
    public function __construct(SiswaService $service)
    {
        $this->service = $service;
    }
    
    //
    public function home()
    {
        return view('home');
    }

    public function getData()
    {
        $siswa = Siswa::all();
        return response()->json($siswa);
    }

    public function search(Request $request)
    {
        $keyword = strtolower($request->input('q'));
        $siswa = Siswa::whereRaw('LOWER(nama) LIKE ?', ["%{$keyword}%"])
            ->get();
        return response()->json($siswa);
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function store(StoreSiswaRequest $request)
    {
        $this->service->createSiswa($request->validated());
        return redirect()->route('home')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $siswa = siswa::where('idsiswa', $id)->firstOrFail();
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'tb' => 'required|numeric',
            'bb' => 'required|numeric',
        ]);

        $siswa = siswa::where('idsiswa', $id)->firstOrFail();
        $siswa->update([
            'nama' => $request->nama,
            'tb' => $request->tb,
            'bb' => $request->bb,
        ]);
        
        return redirect()->route('home')->with('success', 'Data siswa berhasil diupdate');
    }

    public function destroy($id)
    {
        $siswa = siswa::where('idsiswa', $id)->firstOrFail();
        $siswa->delete();
        return redirect()->route('home')->with('success', 'Data siswa berhasil dihapus');
    }
}