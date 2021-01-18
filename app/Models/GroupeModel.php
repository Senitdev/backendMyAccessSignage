<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupeModel extends Model
{
    protected $table= 'groupe';

    // protected $fillabe=[
    //     'id',
    //     'id_groupe',
    //     'id_entite',
    //     'nom',
    //     'droit_afficher',
    //     'droit_editer',
    //     'droit_supprimer',
    //     'created_at',
    //     'updated_at'
    // ];

    protected $guarded = []; 
}
