<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use App\Models\ProductStock;

class Variation extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function product(){

        return $this->belongsTo(Product::class);
    }

    public function color(){

        return $this->belongsTo(Color::class);
    }
    public function size(){
 
        return $this->belongsTo(Size::class); 
    }

    public function stocks() {

        return $this->hasMany(ProductStock::class,'variation_id');
    }

}
