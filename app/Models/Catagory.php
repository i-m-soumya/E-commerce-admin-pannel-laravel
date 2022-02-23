<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catagory extends Model
{
    use HasFactory;
    protected $table='product_category';

    public function subCategory()
    {
        return $this->hasMany(SubCatagory::class, 'category_id', 'id')
            ->where('product_sub_category.is_active','=',1);
    }
}
