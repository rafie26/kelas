<?php
namespace App\Repositories;

use App\Models\Konten;

class KontenRepository
{
    public function getAll()
    {
        return Konten::all();
    }

    public function findById(int $id)
    {
        return Konten::findOrFail($id);
    }
}
