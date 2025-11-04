<?php
namespace App\Repositories;
use App\Models\Siswa;
class SiswaRepository
{
    public function create(array $data)
    {
        $admin = \App\Models\Admin::create([
            'username' => $data['nama'],
            'password' => bcrypt($data['nama']),
            'role' => 'siswa',
        ]);
        $siswa = \App\Models\Siswa::create([
            'id' => $admin->id,
            'nama' => $data['nama'],
            'tb' => $data['tb'],
            'bb' => $data['bb'],
        ]);
        return $siswa;
    }
}
