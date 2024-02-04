<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';
    /**
     * @var string
     */

    /**
     * @var array
     */
    protected $fillable = [
        'blog_id',
        'name',
        'content',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'blog_id' => 'integer',
    ];

    public function article()
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }
}
