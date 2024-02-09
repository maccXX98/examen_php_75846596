<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    public function libros()
    {
        return $this->hasMany(Libro::class);
    }

    use HasFactory;

    protected $fillable = ['nombre'];
    protected $table = 'autores';
}
