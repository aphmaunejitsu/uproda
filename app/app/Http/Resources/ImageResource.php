<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Libs\Traits\BuildImagePath;

class ImageResource extends JsonResource
{
    use BuildImagePath;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'image_hash_id' => $this->image_hash_id,
            'image'         => $this->getImageUrl($this->basename, $this->ext),
            'thumbnail'     => $this->getThumbnailUrl($this->basename, $this->ext),
            'basename'      => $this->basename,
            'ext'           => $this->ext,
            't_ext'         => $this->t_ext,
            'original'      => $this->original,
            'mimetype'      => $this->mimetype,
            'width'         => $this->width,
            'height'        => $this->height,
            'size'          => $this->size,
            'comment'       => $this->comment,
            'image_hash'    => $this->imageHash,
        ];
    }
}
