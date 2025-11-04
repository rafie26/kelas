<?php
namespace App\Services;

use App\Repositories\KbmRepository;

class KbmService
{
    protected $kbmRepository;

    public function __construct(KbmRepository $kbmRepository)
    {
        $this->kbmRepository = $kbmRepository;
    }

    public function getIndexData()
    {
        $role = session('admin_role');
        $adminId = session('admin_id');

        $data = [
            'guru' => null,
            'siswaData' => null,
            'kelasData' => null
        ];

        if ($role === 'guru') {
            $guru = $this->kbmRepository->getGuruById($adminId);
            
            if (!$guru) {
                throw new \Exception('Data guru tidak ditemukan.');
            }
            
            $data['guru'] = $guru;
        } elseif ($role === 'siswa') {
            $siswaData = $this->kbmRepository->getSiswaWithKelas($adminId);
            
            if (!$siswaData || !$siswaData->kelas) {
                throw new \Exception('Anda belum terdaftar di kelas manapun.');
            }
            
            $data['siswaData'] = $siswaData;
            $data['kelasData'] = $siswaData->kelas->walas;
        }

        return $data;
    }

    public function getKbmData(?string $hari = null)
    {
        $role = session('admin_role');
        $adminId = session('admin_id');

        if ($role === 'admin') {
            return $this->kbmRepository->getKbmByHari($hari);
        } elseif ($role === 'guru') {
            $guru = $this->kbmRepository->getGuruById($adminId);
            
            if (!$guru) {
                throw new \Exception('Data guru tidak ditemukan.');
            }
            
            return $this->kbmRepository->getKbmByGuru($guru->idguru, $hari);
        } elseif ($role === 'siswa') {
            $siswaData = $this->kbmRepository->getSiswaWithKelas($adminId);
            
            if (!$siswaData || !$siswaData->kelas) {
                throw new \Exception('Anda belum terdaftar di kelas manapun.');
            }
            
            return $this->kbmRepository->getKbmByKelas($siswaData->kelas->idwalas, $hari);
        } else {
            throw new \Exception('Akses ditolak.');
        }
    }

    public function getKbmByGuru(int $idguru)
    {
        $role = session('admin_role');

        if ($role !== 'admin') {
            throw new \Exception('Akses ditolak. Hanya admin yang dapat melihat jadwal guru lain.');
        }

        return $this->kbmRepository->getGuruWithKbm($idguru);
    }

    public function getKbmByKelas(int $idwalas)
    {
        return $this->kbmRepository->getWalasWithKbm($idwalas);
    }
}
