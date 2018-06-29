<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\ProductSku
 *
 * @property int $id
 * @property int $product_id
 * @property string $title SKU 名称
 * @property string $description SKU 描述
 * @property float $price SKU 价格
 * @property int $stock 库存
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductSku extends Model
{
    protected $fillable = ['title', 'description', 'price', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
