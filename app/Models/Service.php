<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'banner', 'button_name', 'button_address'];

    public function getBannerAttribute ($banner): string
    {
        return Storage::disk('s3_website_assets')->url($banner);
    }
}
