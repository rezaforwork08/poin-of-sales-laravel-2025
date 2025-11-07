<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable =
    [
        'category_id',
        'product_name',
        'product_photo',
        'product_price',
        'product_description',
        'is_active'
    ];

    protected $appends = [
        'is_active_class',
        'is_active_text'
    ];

    public function getIsActiveClassAttribute()
    {
        switch ($this->is_active) {
            case '1':
                return "badge text-bg-primary";
                break;

            default:
                return "badge text-bg-warning";
                break;
        }
    }

    public function getIsActiveTextAttribute()
    {
        switch ($this->is_active) {
            case '1':
                return "Publish";
                break;

            default:
                return "Draft";
                break;
        }
    }




    // one to many : belongsTo
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
