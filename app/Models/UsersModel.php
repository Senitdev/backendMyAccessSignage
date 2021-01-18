<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersModel extends Model
{
    protected $table= 'users';

    public $timestamps = false;
    
    // protected $fillabe=[
    //     'id_user',
    //     'id_entite',
    //     'id_groupe',
    //     'access_level',
    //     'prenom',
    //     'nom',
    //     'tel',
    //     'email',
    //     'password',
    //     'droit_afficher',
    //     'droit_editer',
    //     'droit_supprimer',
    //     'etat'
    // ];

    protected $guarded = [];
}
