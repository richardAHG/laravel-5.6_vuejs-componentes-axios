<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    //
    protected $fillable= [
        'idcategoria','codigo','stock','nombre','precio_venta','descripcion','condicion'
    ];

    public function categoria()
    {
        return $this->belongsTo('App\Categoria');
    }
}
