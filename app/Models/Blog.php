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
        'category',
        'title',
        'image',
        'public_path',
        'content',
        'publish_date',
        'public'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'category' => 'integer',
        'public' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values()
    {
        return $this->hasMany(User::class);
    }
}
