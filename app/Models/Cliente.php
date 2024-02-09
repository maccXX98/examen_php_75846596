<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }

    use HasFactory;

    protected $fillable = ['nombre', 'email', 'celular'];
    protected $table = 'clientes';
}
