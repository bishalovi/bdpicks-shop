<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Size;
use App\Models\ProductStock;
use App\Models\ProductImage;
use App\Models\Type;
use App\Models\Variation;
use App\Models\User;
use App\Models\ProductReview;

class Product extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function category(){

        return $this->belongsTo(Category::class);
    }
  
  	public function user(){

        return $this->belongsTo(User::class);
    }

    public function sizes() {

        return $this->belongsToMany(Size::class,'product_sizes');
    }

    public function brand() {

        return $this->belongsTo(Type::class,'type_id');
    }


    public function stocks() {

        return $this->hasMany(ProductStock::class);
    }

    public function images() {

        return $this->hasMany(ProductImage::class);
    }


    public function variations() {

        return $this->hasMany(Variation::class,'product_id');
    }
    
    public function reviews() {

        return $this->hasMany(ProductReview::class);
    }

    public function variation() {

        return $this->belongsTo(Variation::class,'id','product_id')->orderBy('id');
    }


}
