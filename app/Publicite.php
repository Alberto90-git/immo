<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Publicite extends Model
{
    protected $fillable = [
        'iddirection_ref','idannexe_ref','localisation','Superficie','telephone','price',
        'description','etat','delete_at','status',
        'image_url','image_url2','image_url3','image_url4','published_at',
        'ville','quartier','type_bien','slug','video_url','lat','lng',
        'meta_description','is_sponsored','sponsored_until','transaction_sponsoring',
    ];

    protected $casts = [
        'published_at'    => 'datetime',
        'sponsored_until' => 'datetime',
        'is_sponsored'    => 'boolean',
        'lat'             => 'float',
        'lng'             => 'float',
    ];

    public static function generateSlug(string $title, int $id): string
    {
        $base = Str::slug($title . '-' . $id);
        return $base;
    }

    public function getTypeLabelAttribute(): string
    {
        $types = [
            'appartement' => 'Appartement',
            'maison'      => 'Maison',
            'villa'       => 'Villa',
            'terrain'     => 'Terrain',
            'bureau'      => 'Bureau',
            'commerce'    => 'Local commercial',
            'chambre'     => 'Chambre',
            'studio'      => 'Studio',
        ];
        return $types[$this->type_bien] ?? ucfirst($this->type_bien ?? '');
    }

    public function getIsSponsoredActiveAttribute(): bool
    {
        return $this->is_sponsored && $this->sponsored_until && $this->sponsored_until->isFuture();
    }

    public function scopeSponsored($query)
    {
        return $query->where('is_sponsored', true)
                     ->where('sponsored_until', '>=', now());
    }

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
