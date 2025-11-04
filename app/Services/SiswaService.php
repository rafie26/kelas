<?php
namespace App\Services;
use App\Repositories\SiswaRepository;
class SiswaService
{
    protected $repo;
    
    public function __construct(SiswaRepository $repo)
    {
        $this->repo = $repo;
    }
    
    public function createSiswa(array $data)
    {
        return $this->repo->create($data);
    }
}
