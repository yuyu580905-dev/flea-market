<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Comment;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'condition_id',
        'name',
        'description',
        'price',
        'image',
        'brand',
        'is_sold',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'likes')
            ->withTimestamps();
    }
    public function isLikedBy($user)
    {
        return $this->likedUsers()
            ->where('user_id', $user->id)
            ->exists();
    }
}
