<?php

namespace IdeoLearn\Core\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StorageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'bucket' => $this['bucket'] ?? null,
            'filename' => $this['filename'] ?? null,
            'full_path' => $this['path'] ?? null,
            'url' => $this['url'] ?? null,
        ];
    }
}
