<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title','owner_id'];

    public function items()
    {
        return $this->belongsToMany(Item::class,'items_categories');
    }

    public function owner()
    {
        return $this->belongsTo(User::class,'owner_id');
    }
}
