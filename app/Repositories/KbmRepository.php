<?php
namespace App\Repositories;

use App\Models\Kbm;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Walas;

class KbmRepository
{
    public function getKbmWithRelations()
    {
        return Kbm::with(['guru', 'walas']);
    }

    public function getKbmByHari(?string $hari = null)
    {
        $query = $this->getKbmWithRelations();
        
        if ($hari) {
            $query->where('hari', $hari);
        }
        
        return $query->get();
    }

    public function getKbmByGuru(int $guruId, ?string $hari = null)
    {
        $query = $this->getKbmWithRelations()->where('idguru', $guruId);
        
        if ($hari) {
            $query->where('hari', $hari);
        }
        
        return $query->get();
    }

    public function getKbmByKelas(int $walasId, ?string $hari = null)
    {
        $query = $this->getKbmWithRelations()->where('idwalas', $walasId);
        
        if ($hari) {
            $query->where('hari', $hari);
        }
        
        return $query->get();
    }

    public function getGuruById(int $id)
    {
        return Guru::where('id', $id)->first();
    }

    public function getSiswaWithKelas(int $id)
    {
        return Siswa::where('id', $id)
            ->with(['kelas.walas'])
            ->first();
    }

    public function getGuruWithKbm(int $idguru)
    {
        return Guru::with(['kbm.walas'])->findOrFail($idguru);
    }

    public function getWalasWithKbm(int $idwalas)
    {
        return Walas::with(['kbm.guru'])->findOrFail($idwalas);
    }
}
