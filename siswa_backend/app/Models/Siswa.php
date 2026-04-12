<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai  extends Model
{
    protected $fillable = ['siswa_id', 'kelas', 'mapel', 'nilai'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

}
