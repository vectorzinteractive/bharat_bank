<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Boot the trait.
     * Hooks into Eloquent events for automatic slug generation.
     */
    public static function bootHasSlug()
    {
        static::creating(function ($model) {
            $model->generateSlug();
        });

        static::updating(function ($model) {
            // Regenerate slug only if source field changed
            if ($model->isDirty($model->slugSource)) {
                $model->generateSlug();
            }
        });
    }

    /**
     * Generate a unique slug for the model.
     */
    public function generateSlug()
    {
        $slugColumn = $this->slugColumn ?? 'slug';        // Default column: 'slug'
        $slugLength = $this->slugLength ?? 10;           // Default max length: 50
        $value = $this->{$this->slugSource};

        // Create initial slug
        $slug = Str::slug(substr($value, 0, $slugLength));
        $original = $slug;
        $count = 1;

        // Ensure uniqueness
        while (self::where($slugColumn, $slug)
            ->where('id', '!=', $this->id ?? 0)
            ->exists()) {

            $suffix = '-' . $count++;
            // Truncate original slug to fit max length including suffix
            $slug = Str::limit($original, $slugLength - strlen($suffix), '') . $suffix;
        }

        $this->{$slugColumn} = $slug;
    }
}
