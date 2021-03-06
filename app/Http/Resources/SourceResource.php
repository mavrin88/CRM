<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SourceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'	    => $this->id,
            'name'	    => $this->name,
            'group'	    => $this->group ? $this->group->name : '',
            'group_id'	=> $this->group_id,
            'coment'	=> $this->coment,
        ];
    }
}
