<?php

namespace App\Http\Controllers;

use App\Models\konten;
use App\Repositories\KontenRepository;

class kontenController extends Controller
{
    protected $kontenRepository;

    public function __construct(KontenRepository $kontenRepository)
    {
        $this->kontenRepository = $kontenRepository;
    }
    public function landing()
    {
        $konten = $this->kontenRepository->getAll();
        return view('landing', compact('konten'));
    }

    public function detil($id)
    {
        $datakonten = $this->kontenRepository->findById($id);
        return view('detil', compact('datakonten'));
    }
}
