<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beat extends Model
{
    use HasFactory;

    // id, created_at and updated_at, 

    // protected $table = 'my_flights'; --  table name      wenn nicht      'beats' (snake case + plural)
    // protected $primaryKey = 'flight_id'; primary key     wenn nicht      id

    // protected $attributes = [        default werte für attributes
    //     'delayed' => false,
    // ];

}
