<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\UserFavoriteProducts
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFavoriteProducts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFavoriteProducts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFavoriteProducts whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFavoriteProducts whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserFavoriteProducts whereUserId($value)
 * @mixin \Eloquent
 */
class UserFavoriteProducts extends Model
{
    //
}
