<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'user_id',
        'icon',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'category_id');
    }
}
