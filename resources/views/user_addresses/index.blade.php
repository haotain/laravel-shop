@extends('layouts.app')

@section('title', '收获地址列表')

@section('content')
  <div class="row">
    <div class="col-md-10 offset-md-1">
      <div class="card panel-default">
        <div class="card-header">
          收货地址列表
          <a href="{{ route('user_addresses.create') }}" class="float-right">新增收入地址</a>
        </div>
        <div class="card-body">
          <table class="table table-bodered table-striped">
            <thead>
              <tr>
                <th>收货人</th>
                <th>地址</th>
                <th>邮编</th>
                <th>电话</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              @foreach($addresses as $address)
                <tr>
                  <td>{{ $address->contact_name }}</td>
                  <td>{{ $address->full_address }}</td>
                  <td>{{ $address->zip }}</td>
                  <td>{{ $address->contact_phone }}</td>
                  <td>
                    <a href="{{ route('user_addresses.edit', ['user_address' => $address->id]) }}" role="button" class="btn btn-primary">修改</a>
                    <a href="{{ route('user_addresses.edit', ['user_address' => $address->id]) }}" role="button" class="btn btn-primary">删除</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@stop
