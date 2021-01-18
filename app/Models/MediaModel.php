<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaModel extends Model
{
    protected $table= 'media';

  
    //
    // protected $fillabe=[
    //     'id_media',
    //     'id_entite',
    //     'nom',
    //     'taille',
    //     'type',
    //     'created_at',
    //     'updated_at'
    // ];

      protected $guarded = [];

}
