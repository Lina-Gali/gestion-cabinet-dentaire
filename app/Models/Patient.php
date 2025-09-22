<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'nom_complet',
        'num_telephone',
        'age',
        'maladies',
    ];
}
