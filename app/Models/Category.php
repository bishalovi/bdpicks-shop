<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Type;
use App\Models\Product;
use App\Models\Category;

class Category extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function type(){

        return $this->belongsTo(Type::class);
    }

    public function products(){

        return $this->hasMany(Product::class);
    }

    public function parent(){

        return $this->belongsTo(Category::class,'parent_id');
    }

    public function subcats(){

        return $this->hasMany(Category::class,'parent_id');
    }

}
