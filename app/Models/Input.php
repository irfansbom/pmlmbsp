<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Input extends Model
{
    use HasFactory;
    protected $table = 'input';
    protected $fillable = ['nks', 'dok_diterima', 'dok_diserahkan', 'deskripsi', 'tanggal_laporan', 'kode_pml'];
}
