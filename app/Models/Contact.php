<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ContactsMerge;

class Contact extends Model
{
    use HasFactory,  HasUuids, SoftDeletes;

    protected $table = 'contact';

    protected $fillable = [
        'name',
        'email',
        'Phone',
        'gender',
        'profile_image',
        'doc',
        'custom_field',
    ];

    protected static function booted()
    {
        static::deleting(function ($contact) {
            ContactsMerge::where('contact_uuid', $contact->id)->delete();
        });
    }

    public function mergedContact()
    {
        return $this->hasOne(ContactsMerge::class,'contact_uuid','id');
    }
}
