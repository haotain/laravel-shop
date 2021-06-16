@extends('layouts.app')

@section('title', '商品列表')

@section('content')
  <div class="row">
    <div class="col-lg-10 offset-lg-1">
      <div class="card">
        <div class="card-body">
          <!-- 筛选组件开始 -->
          <form action="{{ route('products.index') }}" class="search-form">
            <div class="form-row">

              <div class="col-md-9">
                <div class="form-row">
                   <!-- 面包屑开始 -->
                  <div class="col-auto category-breadcrumb">
                      <!-- 添加一个名为 全部 的链接，直接跳转到商品列表页 -->
                      <a class="all-products" href="{{ route('products.index') }}">全部</a> >
                      <!-- 如果当前是通过类目筛选的 -->
                      @if ($category)
                      <!-- 遍历这个类目的所有祖先类目，我们在模型的访问器中已经排好序，因此可以直接使用 -->
                      @foreach($category->ancestors as $ancestor)
                        <!-- 添加一个名为该祖先类目名的链接 -->
                        <span class="category">
                          <a href="{{ route('products.index', ['category_id' => $ancestor->id]) }}">{{ $ancestor->name }}</a>
                        </span>
                        <span>&gt;</span>
                      @endforeach
                      <!-- 最后展示出当前类目名称 -->
                        <span class="category">{{ $category->name }}</span><span> ></span>
                        <!-- 当前类目的 ID，当用户调整排序方式时，可以保证 category_id 参数不丢失 -->
                        <input type="hidden" name="category_id" value="{{ $category->id }}">
                      @endif
                    </div>
                    <!-- 面包屑结束 -->
                  <div class="col-auto">
                    <input type="text" class="form-control form-control-sm" name="search" placeholder="搜索"/>
                  </div>
                  <div class="col-auto">
                    <button class="btn btn-primary btn-sm">搜索</button>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <select name="order" class="form-contol form-control-sm float-right">
                  <option value="">排序方式</option>
                  <option value="price_asc">价格从低到高</option>
                  <option value="price_desc">价格从高到低</option>
                  <option value="sold_count_desc">销量从高到低</option>
                  <option value="sold_count_asc">销量从低到高</option>
                  <option value="rating_desc">评价从高到低</option>
                  <option value="rating_ase">评价从低到高</option>
                </select>
              </div>

            </div>
          </form>
          <!-- 筛选组件结束 -->
          <!-- 展示子类目开始 -->
          <div class="filters">
            <!-- 如果当前通过类目筛选， 并且此类目是一个父类目 -->
            @if($category && $category->is_directory)
              <div class="row">
                <div class="col-3 filter-key">子类目：</div>
                <div class="col-9 filter-values">
                <!-- 遍历直接子类目 -->
                @foreach($category->children as $child)
                  <a href="{{ route('products.index', ['category_id' => $child->id]) }}">{{ $child->name }}</a>
                @endforeach
                </div>
              </div>
            @endif
            <!-- 分面搜索结果开始 -->
            <!-- 遍历聚合的商品属性 -->
            @foreach($properties as $property)
            <div class="row">
              <!-- 输出属性名 -->
              <div class="col-3 filter-key">{{ $property['key'] }}：</div>
              <div class="col-9 filter-values">
                <!-- 遍历属性值列表 -->
                @foreach($property['values'] as $value)
                  <a href="javascript: appendFilterToQuery('{{ $property['key'] }}', {{ $value }})">{{ $value }}</a>
                @endforeach
              </div>
            </div>
            @endforeach
            <!-- 分面搜索结果结束 -->
          </div>
          <!-- 展示子类目结束 -->
          <div class="row products-list">
            @foreach($products as $product)
              <div class="col-3 product-item">
                <div class="product-content">
                  <div class="top">
                    <div class="img"><a href="{{ route('products.show', ['product' => $product->id]) }}"><img src="{{ $product->image_url }}" alt=""></a></div>
                    <div class="price"><b>￥</b>{{ $product->price }}</div>
                    <div class="title"><a href="{{ route('products.show', ['product' => $product->id]) }}">{{ $product->title }}</a></div>
                  </div>
                  <div class="bottom">
                    <div class="sold_count">销量<span>{{ $product->sold_count }}笔</span></div>
                    <div class="review_count">评价<span>{{ $product->review_count }}</span></div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
          <div class="float-right">{{ $products->appends($filters)->render() }}</div>
        </div>
      </div>
    </div>
  </div>
@stop

@section('scriptsAfterJs')
<script>
  var filters = {!! json_encode($filters) !!};
  $(document).ready(function() {
    $('.search-form input[name=search]').val(filters.search);
    $('.search-form select[name=order]').val(filters.order);

    $('.search-form select[name=order]').on('change', function() {
      $('.search-form').submit();
    })
  })

  // 定义一个函数， 用于解析当前url 里的参数， 并以 key-value 对象形式返回
  function parseSearch() {
    // 初始化一个空对象
    const searches = {};
    // location.search 会返回 Url 中 ? 以及后面的查询参数
    // substr(1) 将 ? 去除，然后以符号 & 分割成数组，然后遍历这个数组
    location.search.substr(1).split('&').forEach(function(str) {
      // 将字符串以符号 = 分割成数组
      let result = str.split('=');
      // 将数组的第一个值解码之后作为key, 第二个值解码后作为 value 放到之前初始化的对象中
      searches[decodeURIComponent(result[0])] = decodeURIComponent(result[1]);
    });

    return searches;
  }

  // 根据 key-value 对象构建查询参数
  function buildSearch(searches) {
    // 初始化字符串
    let query = '?';
    // 遍历searches 对象
    _.forEach(searches, function(value, $key) {
      query += encodeURIComponent(key) + '=' encodeURIComponent(value) + '&';
    });
    // 去除最末尾的 & 符号
    return query.substr(0, query.length-1);
  }

  // 将新的 filter 追加到当前的 Url 中

</script>
@endsection

