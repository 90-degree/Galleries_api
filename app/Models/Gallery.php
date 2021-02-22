<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'title', 'description'];

    public function imageUrls()
    {
        return $this->hasMany(ImageUrl::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
