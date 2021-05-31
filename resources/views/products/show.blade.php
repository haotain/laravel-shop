@extends('layouts.app')

@section('title', '商品详情页')

@section('content')
  <div class="row">
    <div class="col-lg-10 offset-lg-1">
      <div class="card">
        <div class="card-body product-info">
          <div class="row">
            <div class="col-5">
              <img class="cover" src="{{ $product->image_url }}" alt=""/>
            </div>

            <div class="col-7">
              <div class="title">{{ $product->title }}</div>
              <!-- 众筹商品模块开始 -->
              @if($product->type === \App\Models\Product::TYPE_CROWDFUNDING)
                <div class='crowdfunding-info'>
                  <div class="have-text">已筹到</div>
                  <div class="total-amount"><span class="symbol">￥</span>{{ $product->crowdfunding->total_amount }}</div>
                  <!-- 这里使用了 Bootstrap 的进度条组件 -->
                  <div class="progress">
                    <div class="progress-bar grogress-bar-success progress-bar-striped"
                      role="progressbar"
                      aria-valuenow="{{ $product->crowdfunding->percent }}"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="min-width: 1em; width: {{ min($product->crowdfunding->percent, 100) }}%">                    >
                    </div>
                  </div>
                  <div class="progress-info">
                    <span class="current-progress">当前进度: {{ $product->crowdfunding->percent }}%</span>
                    <span class="float-right user-count">{{ $product->crowdfunding->user_count }}名支持者</span>
                  </div>
                  <!-- 如果众筹状态是众筹中，则输出提示语 -->
                  @if ($product->crowdfunding->status === \App\Models\CrowdfundingProduct::STATUS_FUNDING)
                  <div>此项目必须在
                    <span class="text-red">{{ $product->crowdfunding->end_at->format('Y-m-d H:i:s') }}</span>
                    前得到
                    <span class="text-red">￥{{ $product->crowdfunding->target_amount }}</span>
                    的支持蔡可成功，
                    <!-- Carbon 对象的 diffForHumans() 方法可以计算出与当前时间的相对时间，更人性化 -->
                    筹款将在<span class="text-red">{{ $product->crowdfunding->end_at->diffForHumans(now()) }}</span>结束！
                  </div>
                  @endif
                </div>
              @else
                <!-- 原普通商品模块开始 -->
                <div class="price"><label>价格</label><em>￥</em><span>{{ $product->price }}</span></div>
                <div class="sales_and_reviews">
                  <div class="sold_count">累计销售 <span class="count">{{ $product->price }}</span></div>
                  <div class="review_count">累计评价 <span class="count">{{ $product->review_count }}</span></div>
                  <div class="rating" title="评分 {{ $product->rating }}">评分 <span class="count">{{ str_repeat('★', floor($product->rating)) }}{{ str_repeat('☆', 5 - floor($product->rating)) }}</span></div>
                </div>
                <!-- 原普通商品模块结束 -->
              @endif
              <!-- 众筹商品模块结束 -->
              <div class="skus">
                <label>选择</label>
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                  @foreach($product->skus as $sku)
                    <label class="btn sku-btn" title="{{ $sku->description }}" data-price="{{ $sku->price }}" data-stock="{{ $sku->stock }}" data-toggle="tooltip"  data-placement="bottom">
                      <input type="radio" name="skus" autocomplete="off" value="{{ $sku->id }}">{{ $sku->title }}
                    </label>
                  @endforeach
                </div>
              </div>

              <div class="cart_amount"><label>数量</label><input type="text" class="form-control form-control-sm" value="1"><span>件</span><span class="stock"></span></div>

              <div class="buttons">
                @if($favored)
                <button class="btn btn-danger btn-disfavor">取消收藏</button>
                @else
                  <button class="btn btn-success btn-favor">❤ 收藏</button>
                @endif
                <!-- 众筹商品下单按钮开始 -->
                @if($product->type === \App\Models\CrowdfundingProduct::STATUS_FUNDING)

                  @if(Auth::check())

                    @if($product->crowdfunding->status === \App\Models\CrowdfundingProduct::STATUS_FUNDING)
                      <button class="btn btn-primary btn-crowdfunding">参与众筹</button>
                    @else
                      <button class="btn btn-primary disabled">
                        {{ \App\Models\CrowdfundingProduct::$statusMap[$product->crowdfunding->status] }}
                      </button>

                    @endif

                  @else
                    <a class="btn btn-primary" href="{{ route('login') }}">请先登录</a>
                  @endif

                @else
                  <button class="btn btn-primary btn-add-to-cart">加入购物车</button>
                @endif
                <!-- 众筹商品下单按钮结束 -->
              </div>

            </div>
          </div>


          <div class="product-detail">
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <a class='nav-link avtive' href="#product-detail-tab" aria-controls="product-detail-tab" role="tab" data-toggle="tab" aria-selected="true">商品详情</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#product-reviews-tab" aria-controls="product-reviews-tab" role="tab" data-toggle="tab" aria-selected="false">用户评价</a>
              </li>
            </ul>
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="product-detail-tab">
                {!! $product->description !!}
              </div>
              <div role="tabpanel" class="tab-pane" id="product-reviews-tab">
                <!-- 评论列表开始 -->
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <td>用户</td>
                      <td>商品</td>
                      <td>评分</td>
                      <td>评价</td>
                      <td>时间</td>
                    </tr>
                    </thead>
                    <tbody>
                      @foreach($reviews as $review)
                      <tr>
                        <td>{{ $review->order->user->name }}</td>
                        <td>{{ $review->productSku->title }}</td>
                        <td>{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</td>
                        <td>{{ $review->review }}</td>
                        <td>{{ $review->reviewed_at->format('Y-m-d H:i') }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  <!-- 评论列表结束 -->

              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection


@section('scriptsAfterJs')
<script>
  $(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});
    $('.sku-btn').click(function () {
      $('.product-info .price span').text($(this).data('price'));
      $('.product-info .stock').text('库存：' + $(this).data('stock') + '件');
    });

    // 监听收藏按钮的点击事件
    $('.btn-favor').click(async function(){
      // 发起一个 post ajax 请求，请求url 通过后端 route() 函数生成
      try {
        await axios.post("{{ route('products.favor', ['product' => $product->id]) }}")
        // swal('操作成功', '', 'success')
        //   .then(function () {  // 这里加了一个 then() 方法
        //       location.reload();
        //     });
        location.reload();
      } catch (err) {

        if (err.response && err.response.status === 401) {
          swal('请先登录', '', 'error');
        } else if (error.response && (error.response.data.msg || error.response.data.message)) {
          // 其他有 msg 或者 message 字段的情况，将 msg 提示给用户
          swal(error.response.data.msg ? error.response.data.msg : error.response.data.message, '', 'error');
        } else {
          // 其他情况应该是系统挂了
          swal('系统错误', '', 'error');
        }
      }
    })
    // 取消收藏
    $('.btn-disfavor').click(async function () {

      await axios.delete("{{ route('products.disfavor', ['product' => $product->id]) }}")
      // swal('操作成功', '', 'success')
      //   .then(function () {  // 这里加了一个 then() 方法
      //       location.reload();
      //     });
      location.reload();
    });

    // 加入购物车按钮点击事件
    $('.btn-add-to-cart').click(async function() {
      // 请求加入购物车接口
      try {
        await axios.post("{{ route('cart.add') }}", {
          sku_id: $('label.active input[name=skus]').val(),
          amount: $('.cart_amount input').val(),
        })
        swal('加入购物车成功', '', 'success')
          .then(function() {
            location.href = "{{ route('cart.index') }}"
          });

      }  catch (err) {
        if (err.response.status === 401) {
          // http 状态码为 401 代表用户未登陆
          swal('请先登录', '', 'error');

        } else if (err.response.status === 422) {
          // http 状态码为 422 代表用户输入校验失败
          var html = '<div>';
          _.each(err.response.data.errors, function(errors) {
            _.each(errors, function(error) {
              html += error + '<br>';
            })
          })
          html += '</div>'
          swal({content: $(html)[0], icon: 'error'})
        } else {
          // 其他情况应该是系统挂了
          swal('系统错误', '', 'error');
        }
      }

    })

  });

</script>
@endsection
