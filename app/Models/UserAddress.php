<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\UserAddress
 *
 * @property int $id
 * @property int $user_id
 * @property string $province 省
 * @property string $city 市
 * @property string $district 区
 * @property string $address 详细地址
 * @property int $zip 邮编
 * @property string $contact_name 收货人
 * @property string $contact_phone 收货人电话
 * @property \Carbon\Carbon|null $last_used_at 最后一次使用时间
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $full_address
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserAddress whereZip($value)
 * @mixin \Eloquent
 */
class UserAddress extends Model
{
    protected $fillable = [
        'province',
        'city',
        'district',
        'address',
        'zip',
        'contact_name',
        'contact_phone',
        'last_used_at',
    ];
    protected $dates = ['last_used_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute()
    {
        return "{$this->province}{$this->city}{$this->district}{$this->address}";
    }
}
