@extends('layouts.app')

@section('title', '新增收获地址')

@section('content')
  <div  class="row">
    <div class="col-md-10 offset-lg-1">
      <div class="card">
        <div class="header">
          <h2 class="text-center">新增收货地址</h2>
        </div>
        <div class="card-body">
          <!-- 输出后端报错开始 -->
          @include('shared._errors')
          <!-- 输出后端报错结束 -->
          <user-addresses-create-and-edit>
            <template v-slot:default="selectDistrict">
              <form class="form-horizontal" role="form" action="{{ route('user_addresses.store') }}" method="post">
                <!-- 引入 csrf token 字段 -->
                {{ csrf_field() }}
                <!-- 插入了 3 个隐藏的字段 -->
                <!-- 通过 v-model 与 user-addresses-create-and-edit 组件里的值关联起来 -->
                <!-- 当组件中的值变化时，这里的值也会跟着变 -->
                <input type="hidden" name="province" v-model="selectDistrict.province">
                <input type="hidden" name="city" v-model="selectDistrict.city">
                <input type="hidden" name="district" v-model="selectDistrict.district">
                <div class="form-group row">
                  <label class="col-form-label text-md-right col-sm-2">详细地址</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="address" value="{{ old('address', $address->address) }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label text-md-right col-sm-2">邮编</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="zip" value="{{ old('zip', $address->zip) }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label text-md-right col-sm-2">姓名</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="contact_name" value="{{ old('contact_name', $address->contact_name) }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-form-label text-md-right col-sm-2">电话</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="contact_phone" value="{{ old('contact_phone', $address->contact_phone) }}">
                  </div>
                </div>
                <div class="form-group row text-center">
                  <div class="col-12">
                    <button type="submit" class="btn btn-primary">提交</button>
                  </div>
                </div>
              </form>
            </template>
          </user-addresses-create-and-edit>
        </div>
      </div>
    </div>
  </div>
@stop
