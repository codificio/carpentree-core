<?php

namespace Carpentree\Core\Models;

use Carpentree\Core\Models\Category\Translation;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Kalnoy\Nestedset\NestedSet;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;
    use Translatable;

    public $translationModel = Translation::class;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        NestedSet::LFT,
        NestedSet::RGT,
        NestedSet::PARENT_ID
    ];

    public $translatedAttributes = [
        'slug',
        'name',
        'description'
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        NestedSet::LFT => 'integer',
        NestedSet::RGT => 'integer',
        NestedSet::PARENT_ID => 'integer'
    ];

    /**
     * Get all attached models of the given class to the category.
     *
     * @param string $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function entries(string $class): MorphToMany
    {
        return $this->morphedByMany($class,
            'categorizable',
            'categorizables',
            'category_id',
            'categorizable_id'
        );
    }

}
