<?php

namespace App\Http\Controllers;

use App\Events\OrderPaid;
use App\Exceptions\InvalidRequestException;
use App\Models\Order;
use Illuminate\Support\Facades\Request;
use Yansongda\Pay\Pay;

class PaymentController extends Controller
{
    public static function alipayConfig()
    {
        return array_merge(config('pay.alipay'), [
            'notify_url' => route('payment.alipay.notify'),
            'return_url' => route('payment.alipay.return'),
        ]);
    }

    protected function afterPaid(Order $order)
    {
        event(new OrderPaid($order));
    }

    public function payByAlipay(Order $order, Request $request)
    {
        // 判断订单是否属于当前用户
        $this->authorize('own', $order);
        // 订单已支付或者已关闭
        if ($order->paid_at || $order->closed)
        {
            throw new InvalidRequestException('订单状态不正确');
        }

        info($this->alipayConfig());
        // 调用支付宝的网页支付
        return Pay::alipay($this->alipayConfig())->web([
            'out_trade_no' => $order->no, // 订单编号，需保证在商户端不重复
            'total_amount' => $order->total_amount, // 订单金额，单位元，支持小数点后两位
            'subject' => '支付 Laravel Shop 的订单：' . $order->no, // 订单标题
        ]);
    }

    /**
     * 前端回调页面
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    public function alipayReturn()
    {
        try
        {
            // 校验提交的参数是否合法
            Pay::alipay($this->alipayConfig())->verify();
        } catch (\Exception $e)
        {
            return view('pages.error', ['msg' => '数据不正确']);
        }

        return view('pages.success', ['msg' => '付款成功']);
    }


    /**
     * 服务器端回调
     * @return string|\Symfony\Component\HttpFoundation\Response
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    public function alipayNotify()
    {
        // 校验输入参数
        $data = Pay::alipay($this->alipayConfig())->verify();

        // $data->out_trade_no 拿到订单流水号，并在数据库中查询
        $order = Order::where('no', $data->out_trade_no)->first();
        // 正常来说不太可能出现支付了一笔不存在的订单，这个判断只是加强系统健壮性。
        if (!$order)
        {
            return 'fail';
        }
        // 如果这笔订单的状态已经是已支付
        if ($order->paid_at)
        {
            // 返回数据给支付宝
            return Pay::alipay($this->alipayConfig())->success();
        }

        $order->update([
            'paid_at' => now(), // 支付时间
            'payment_method' => 'alipay', // 支付方式
            'payment_no' => $data->trade_no, // 支付宝订单号
        ]);

        $this->afterPaid($order);

        return Pay::alipay($this->alipayConfig())->success();
    }
}
