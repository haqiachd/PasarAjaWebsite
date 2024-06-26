<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $table = "sp_24_promo";
    protected $primaryKey = "id_promo";
    public $incrementing = true; 
    public $timestamps = true;

    protected $fillable = [
        'id_user',
    ];
}
