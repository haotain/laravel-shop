<?php

namespace App\Http\Requests;

use App\Exceptions\InvalidRequestException;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;

class SeckillOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'address_id' => [
            //     'required',
            //     Rule::exists('user_addresses', 'id')->where('user_id', $this->user()->id)
            // ],
            // 将原本的 address_id 删除。由于我们在订单中保存的是收货地址的具体信息而不是收货地址 ID，因此我们可以把秒杀接口的 address_id 参数替换成收货地址的详细信息，这样就可以避免掉这个 SQL 查询。
            'address.province'      => 'required',
            'address.city'          => 'required',
            'address.district'      => 'required',
            'address.address'       => 'required',
            'address.zip'           => 'required',
            'address.contact_name'  => 'required',
            'address.contact_phone' => 'required',
            'sku_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    // 从 Redis 中读取数据
                    $stock = Redis::get('seckill_sku_' . $value);
                    if (is_null($stock)) {
                        return $fail('该商品不存在');
                    }
                    // 判断库存
                    if ($stock < 1) {
                        return $fail('该商品已售完');
                    }

                    // 大多数用户在上面的逻辑里就被拒绝了, 因此下方的 SQL 查询不会对整体性能有太大影响
                    if (!$sku = ProductSku::find($value)) {
                        return $fail('该商品不存在');
                    }
                    // if (!$sku->product->on_sale) {
                    //     return $fail('该商品未上架');
                    // }
                    // if ($sku->product->type !== Product::TYPE_SECKILL) {
                    //     return $fail('该商品不支持秒杀');
                    // }
                    // if ($sku->stock < 1) {
                    //     return $fail('该商品已售完');
                    // }
                    if ($sku->product->seckill->is_before_start) {
                        return $fail('秒杀尚未开始');
                    }
                    if ($sku->product->seckill->is_after_end) {
                        return $fail('秒杀已经结束');
                    }

                    // 延迟校验身份 当秒杀商品有剩余库存时才校验登录凭证
                    if (!$user = Auth::user()) {
                        throw new AuthenticationException('请先登录');
                    }
                    if (!$user->email_verified_at) {
                        throw new InvalidRequestException('请先验证邮件');
                    }

                    if ($order = Order::query()
                        // 筛选出当前用户的订单
                        ->where('user_id', $this->user()->id)
                        ->whereHas('items', function ($query) use ($value) {
                            // 筛选出包含当前 SKU 的订单
                            $query->where('product_sku_id', $value);
                        })
                        ->where(function ($query) {
                            // 已支付的订单
                            $query->whereNotNull('paid_at')
                                // 或者未关闭的订单
                                ->orWhere('closed', false);
                        })
                        ->first()) {
                            if ($order->paid_at) {
                                return $fail('你已经抢购了该商品');
                            }

                            return $fail('你已经下单了该商品，请到订单页面支付');
                        }

                }
            ]
        ];
    }
}
