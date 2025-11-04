<?php
namespace App\Services;

use App\Repositories\AdminRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function login(array $credentials)
    {
        $admin = $this->adminRepository->findByUsername($credentials['username']);

        if ($admin && Hash::check($credentials['password'], $admin->password)) {
            // Set session
            session([
                'admin_id' => $admin->id,
                'admin_username' => $admin->username,
                'admin_role' => $admin->role
            ]);
            return true;
        }

        return false;
    }

    public function logout()
    {
        session()->forget(['admin_id', 'admin_username', 'admin_role']);
    }

    public function getHomeData()
    {
        $role = session('admin_role');
        $id = session('admin_id');

        $data = [
            'siswa' => $this->adminRepository->getAllSiswaWithRelations(),
            'guru' => null,
            'siswaLogin' => null
        ];

        if ($role === 'guru') {
            $data['guru'] = $this->adminRepository->getGuruWithRelations($id);
        } elseif ($role === 'siswa') {
            $data['siswaLogin'] = $this->adminRepository->findSiswaById($id);
        }

        return $data;
    }
}
