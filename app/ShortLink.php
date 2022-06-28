<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShortLink extends Model
{
    use SoftDeletes;
    protected $table = 'short_links';
    protected $fillable = ['long_url', 'short_code', 'number_of_hit',
        'custom_short_code'];
    protected $dates = ['deleted_at'];

}
