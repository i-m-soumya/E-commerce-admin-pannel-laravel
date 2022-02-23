<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use App\Models\Catagory;

class AndroidSearchFilterCategory extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->fetchCategoryAndSubCategory($this['category_id']);
    }

    public function fetchCategoryAndSubCategory($category_id) {
        return Catagory::with('subCategory')
            ->where('product_category.id','=',$category_id)
            ->select('product_category.id','product_category.name')
            ->first();
    }
}
