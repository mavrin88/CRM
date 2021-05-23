<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KassaOperationTypeForKassaSettings extends JsonResource
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

            'branch'	=> $this->branch,
            'groupid'	    => $this->group ? $this->group : '',
            'comin'	=> $this->coming,
            'out'	    => $this->out,
            'cash'	    => $this->cash,
            'beznal'	=> $this->beznal,
            'coment'	=> $this->coment,
        ];
    }
}
