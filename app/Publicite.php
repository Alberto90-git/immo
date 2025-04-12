<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicite extends Model
{
    protected $fillable = ['iddirection_ref','idannexe_ref','localisation','Superficie','telephone','price','description','etat','delete_at','status'
                        ,'image_url'];
}
