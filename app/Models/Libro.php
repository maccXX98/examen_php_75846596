<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{

    public function autor()
    {
        return $this->belongsTo(Autor::class);
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }

    use HasFactory;

    protected $fillable = ['titulo', 'autor_id', 'lote', 'descripcion'];
    protected $table = 'libros';
}
