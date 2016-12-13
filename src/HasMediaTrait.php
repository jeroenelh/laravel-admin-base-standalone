<?php

namespace Microit\LaravelAdminBaseStandalone;

trait HasMediaTrait
{
    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medium()
    {
        return $this->belongsTo(Medium::class);
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function media()
    {
        return $this->morphMany(Medium::class, 'mediable')->orderBy('position', 'desc');
    }
}
