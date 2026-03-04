<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicite extends Model
{
    protected $fillable = [
        'iddirection_ref','idannexe_ref','localisation','Superficie','telephone','price',
        'description','etat','delete_at','status','image_url','image_url2','image_url3','image_url4','published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function scopeNotExpired($query)
    {
        return $query->where('published_at', '>=', now()->subWeek());
    }

    public function scopeNotDeleted($query)
    {
        return $query->whereNull('delete_at');
    }

    public function getImagesAttribute()
    {
        $images = [];
        foreach (['image_url', 'image_url2', 'image_url3', 'image_url4'] as $field) {
            if (!empty($this->{$field})) {
                $images[] = $this->{$field};
            }
        }
        return $images;
    }

    public function getImageCountAttribute()
    {
        return count($this->images);
    }

    public function getFirstFreeSlotAttribute()
    {
        $slots = ['image_url', 'image_url2', 'image_url3', 'image_url4'];
        foreach ($slots as $slot) {
            if (empty($this->{$slot})) {
                return $slot;
            }
        }
        return null;
    }
}
