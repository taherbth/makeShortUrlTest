<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Invoice extends Model
{
    use HasFactory ,SearchableTrait;

    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'invoices.institution_name' => 10,
            'invoices.admin_name' => 7,

        ],
    ];

    protected $fillable = [
        'tax', 'base_price', 'quantity', 'total_paid', 'status', 'subscription_id', 'cancel_at', 'institution_name','admin_name'
    ];

    public function school_inv(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class,'institution_id','id');
    }
}
