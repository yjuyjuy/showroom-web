<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GucciCategory extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'gucci';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    public function products()
    {
        return $this->hasMany(GucciProduct::class, 'category_id', 'id');
    }
    public function parent()
    {
        return $this->belongsTo(GucciCategory::class, 'parent_id', 'id');
    }
    public function children()
    {
        return $this->hasMany(GucciCategory::class, 'parent_id', 'id');
    }
    public function getTranslatedDescriptionAttribute()
    {
        return implode(' - ', array_map('__', explode('-', $this->description ?? 'uncategorized')));
    }
}
