<?php

namespace IdeoLearn\Core\Traits;

use Illuminate\Support\Str;
trait Uuid
{

    public function scopeUuid($query, $uuid)
    {
        return $query->where($this->getUuidName(), $uuid);
    }

    public function getUuidName(): string
    {
        return property_exists($this, 'uuidName') ? $this->uuidName : 'uuid';
    }

    protected static function bootUuid(): void
    {
        static::creating(function ($model) {
            $model->{$model->getUuidName()} = Str::uuid();
        });
    }
}
