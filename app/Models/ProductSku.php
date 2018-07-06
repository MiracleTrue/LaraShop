<?php

namespace App\Models;

use App\Exceptions\InternalException;
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
 * @property string $sku_description SKU 描述
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductSku whereSkuDescription($value)
 */
class ProductSku extends Model
{
    protected $fillable = ['title', 'sku_description', 'price', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function decreaseStock($amount)
    {
        if ($amount < 0) {
            throw new InternalException('减库存不可小于0');
        }

        return $this->newQuery()->where('id', $this->id)->where('stock', '>=', $amount)->decrement('stock', $amount);
    }

    public function addStock($amount)
    {
        if ($amount < 0) {
            throw new InternalException('加库存不可小于0');
        }
        $this->increment('stock', $amount);
    }
}
