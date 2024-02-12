<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videotron extends Model
{
    use HasFactory;

    protected $table = 'videotron';



    public function getImageAttribute($value)
    {
        return url($value);
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
}
