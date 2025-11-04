<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Support\Facades\Hash;

class AdminRepository
{
    public function findByUsername(string $username)
    {
        return Admin::where('username', $username)->first();
    }

    public function createAdmin(array $data)
    {
        return Admin::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);
    }

    public function createSiswa(int $adminId, array $data)
    {
        return Siswa::create([
            'id' => $adminId,
            'nama' => $data['nama'],
            'tb' => $data['tb'],
            'bb' => $data['bb'],
        ]);
    }

    public function createGuru(int $adminId, array $data)
    {
        return Guru::create([
            'id' => $adminId,
            'nama' => $data['nama'],
            'mapel' => $data['mapel'],
        ]);
    }

    public function getAllSiswaWithRelations()
    {
        return Siswa::with(['kelas.walas.guru'])->get();
    }

    public function getGuruWithRelations(int $id)
    {
        return Guru::where('id', $id)
            ->with(['walas.kelas.siswa'])
            ->first();
    }

    public function findSiswaById(int $id)
    {
        return Siswa::where('id', $id)
            ->with(['kelas.walas.guru'])
            ->first();
    }
}