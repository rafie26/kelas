<?php
namespace App\Services;

use App\Repositories\AdminRepository;
use Illuminate\Support\Facades\DB;

class RegistrationService
{
    protected $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function register(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Create admin account
            $admin = $this->adminRepository->createAdmin($data);

            // Create role-specific data
            if ($data['role'] === 'siswa') {
                $this->adminRepository->createSiswa($admin->id, $data);
            } elseif ($data['role'] === 'guru') {
                $this->adminRepository->createGuru($admin->id, $data);
            }

            return $admin;
        });
    }
}
