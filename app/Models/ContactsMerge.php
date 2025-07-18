<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Contact;

class ContactsMerge extends Model
{
    use HasFactory,  HasUuids, SoftDeletes;

    protected $table = 'contacts_merge';

    protected $fillable = [
        'contact_uuid',
        'contact_child_uuid',
        'email',
        'Phone',
        'custom_field',
    ];

    public function mergedContact()
    {
        return $this->belongsTo(Contact::class,'contact_uuid','id');
    }

    // public function child()
    // {
    //     return Contact::where('id', $this->contact_child_uuid)
    //         ->where('is_master', 0)
    //         ->first();
    // }

    public function child()
    {
        return $this->belongsTo(Contact::class, 'contact_child_uuid', 'id');
    }
}
