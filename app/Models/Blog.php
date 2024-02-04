<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blog';

    /**
     * @var string
     */

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'image',
        'public_path',
        'content',
        'publication_date',
        'public'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'category_id' => 'integer',
        'public' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'blog_id');
    }
}
