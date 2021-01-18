<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleModel extends Model
{
    protected $table= 'module';

    public $timestamps = true;

    protected $guarded = [];
}
