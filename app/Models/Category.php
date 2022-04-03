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
        return $this->belongsToMany(Item::class)->as('items');
    }

    public function owner()
    {
        return $this->belongsTo(User::class)->as('owner');
    }
}
