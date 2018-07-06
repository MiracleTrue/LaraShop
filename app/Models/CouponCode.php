<?php

namespace App\Models;

use App\Exceptions\CouponCodeUnavailableException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\CouponCode
 *
 * @property int $id
 * @property string $name 优惠券的标题
 * @property string $code 优惠码，用户下单时输入
 * @property string $type 优惠券类型，支持固定金额和百分比折扣
 * @property float $value 折扣值，根据不同类型含义不同
 * @property int $total 全站可兑换的数量
 * @property int $used 当前已兑换的数量
 * @property float $min_amount 使用该优惠券的最低订单金额
 * @property \Carbon\Carbon|null $not_before 在这个时间之前不可用
 * @property \Carbon\Carbon|null $not_after 在这个时间之后不可用
 * @property bool $enabled 优惠券是否生效
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $description
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CouponCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CouponCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CouponCode whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CouponCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CouponCode whereMinAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CouponCode whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CouponCode whereNotAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CouponCode whereNotBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CouponCode whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CouponCode whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CouponCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CouponCode whereUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CouponCode whereValue($value)
 * @mixin \Eloquent
 */
class CouponCode extends Model
{
    //
    // 用常量的方式定义支持的优惠券类型
    const TYPE_FIXED = 'fixed';
    const TYPE_PERCENT = 'percent';

    public static $typeMap = [
        self::TYPE_FIXED => '固定金额',
        self::TYPE_PERCENT => '比例',
    ];

    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'total',
        'used',
        'min_amount',
        'not_before',
        'not_after',
        'enabled',
    ];
    protected $casts = [
        'enabled' => 'boolean',
    ];
    // 指明这两个字段是日期类型
    protected $dates = ['not_before', 'not_after'];

    protected $appends = ['description'];

    public function getDescriptionAttribute()
    {
        $str = '';

        if ($this->min_amount > 0)
        {
            $str = '满' . str_replace('.00', '', $this->min_amount);
        }
        if ($this->type === self::TYPE_PERCENT)
        {
            return $str . '优惠' . str_replace('.00', '', $this->value) . '%';
        }

        return $str . '减' . str_replace('.00', '', $this->value);
    }


    public static function findAvailableCode($length = 16)
    {
        do
        {
            // 生成一个指定长度的随机字符串，并转成大写
            $code = strtoupper(Str::random($length));
            // 如果生成的码已存在就继续循环
        } while (self::query()->where('code', $code)->exists());

        return $code;
    }

    /**
     * 检验有效性
     * @param User $user
     * @param null $orderAmount
     * @throws CouponCodeUnavailableException
     */
    public function checkAvailable(User $user, $orderAmount = null)
    {
        if (!$this->enabled)
        {
            throw new CouponCodeUnavailableException('优惠券不存在');
        }

        if ($this->total - $this->used <= 0)
        {
            throw new CouponCodeUnavailableException('该优惠券已被兑完');
        }

        if ($this->not_before && $this->not_before->gt(now()))
        {
            throw new CouponCodeUnavailableException('该优惠券现在还不能使用');
        }

        if ($this->not_after && $this->not_after->lt(now()))
        {
            throw new CouponCodeUnavailableException('该优惠券已过期');
        }

        if (!is_null($orderAmount) && $orderAmount < $this->min_amount)
        {
            throw new CouponCodeUnavailableException('订单金额不满足该优惠券最低金额');
        }

        $used = Order::where('user_id', $user->id)
            ->where('coupon_code_id', $this->id)
            ->where(function ($query) {
                $query
                    ->where(function ($query) {
                        $query->whereNull('paid_at')->where('closed', false);
                    })
                    ->orWhere(function ($query) {
                        $query->whereNotNull('paid_at')->where('refund_status', Order::REFUND_STATUS_PENDING);
                    });
            })->exists();
        if ($used)
        {
            throw new CouponCodeUnavailableException('你已经使用过这张优惠券了');
        }
    }

    /**
     * 获得优惠后的价格
     * @param $orderAmount
     * @return mixed|string
     */
    public function getAdjustedPrice($orderAmount)
    {
        // 固定金额
        if ($this->type === self::TYPE_FIXED)
        {
            // 为了保证系统健壮性，我们需要订单金额最少为 0.01 元
            return max(0.01, $orderAmount - $this->value);
        }

        return number_format($orderAmount * (100 - $this->value) / 100, 2, '.', '');
    }

    /**
     * 改变使用量
     * @param bool $increase
     * @return int
     */
    public function changeUsed($increase = true)
    {
        // 传入 true 代表新增用量，否则是减少用量
        if ($increase)
        {
            // 与检查 SKU 库存类似，这里需要检查当前用量是否已经超过总量
            return $this->newQuery()->where('id', $this->id)->where('used', '<', $this->total)->increment('used');
        } else
        {
            return $this->decrement('used');
        }
    }

}
