<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use App\Models\Brand;

class AndroidSearchFilterBrand extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->fetchBrands($this['brand_id']);
    }


    public function fetchBrands($brand_id) {
        return Brand::where('brands.id','=',$brand_id)
            ->select('id','name')
            ->where('is_active','=',1)
            ->first();
    }
}
