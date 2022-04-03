<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

     protected $fillable = ['title','desc','owner_id','is_done'];

    public function categories()
    {
        return $this->belongsToMany(Category::class)->as('categories');
    }

    public function owner()
    {
        return $this->belongsTo(User::class)->as('owner');
    }

}
