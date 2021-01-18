<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgendaModel extends Model
{
    protected $table= 'agenda';

    // protected $fillabe=[
    //     'id',
    //     'id_agenda',
    //     'id_entite',
    //     'nom',
    //     'created_at',
    //     'updated_at'
    // ];

    protected $guarded = []; 
}
