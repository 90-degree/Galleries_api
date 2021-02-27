<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\returnSelf;

class Gallery extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'title', 'description'];
    
    /**
     * relationships...
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function imageUrls()
    {
        return $this->hasMany(ImageUrl::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    /**
     * filters / scopes...
     */
    public function scopeSearchFilter($query,$filterText)
    {
        if(!$filterText){
            return $query;
        }
        return $query->where("title", "LIKE", "%$filterText%")
                    ->orWhere("description", "LIKE", "%$filterText%")
                    ->orWhereHas('user',function($query) use($filterText){
                        return $query->where("first_name", "LIKE", "%$filterText%")
                                    ->orWhere("last_name", "LIKE", "%$filterText%");
                    });
    }
    public function scopeAuthorFilter($query,$id)
    {
        if(!$id){
            return $query;
        }
        return $query->where('user_id',$id);
    }

}
