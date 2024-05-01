<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends LocalizedModel
{
    use HasFactory;

    protected $localizedAttributes = [
        "description"
    ];

    public $timestamps = false;

    protected $fillable = [
        "start_date",
        "end_date",
        "description_en",
        "description_fr",
    ];
}
