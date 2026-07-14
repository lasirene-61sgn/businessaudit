<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'icon', 'sort_order'];

    /**
     * Get all questions assigned to this business category.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->where('is_active', true);
    }
}
