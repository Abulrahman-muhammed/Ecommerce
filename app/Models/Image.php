<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\ProductImageUsage;
class Image extends Model
{
    protected $fillable = ['path', 'imageable_id', 'imageable_type','usage'];


    public function imageable()
    {
        return $this->morphTo();
    }

}
