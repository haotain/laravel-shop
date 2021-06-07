@extends('layouts.app')
@section('title', '分期付款列表')

@section('content')
<div class="row">
  <div class="col-10 offset-1">
    <div class="card">
      <div class="card-header text-center">
        <h2>分期付款列表</h2>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <td>编号</td>
              <td>金额</td>
              <td>期数</td>
              <td>费率</td>
              <td>状态</td>
              <td>操作</td>
            </tr>
          </thead>
          <tbody>
          @foreach ($installments as $installment)
            <tr>
              <td>{{ $installment->no }}</td>
              <td>￥{{ $installment->total_amount }}</td>
              <td>{{ $installment->count }}</td>
              <td>{{ $installment->fee_rate }}</td>
              <td>{{ \App\Models\Installment::$statusMap[$installment->status] }}</td>
              <td><a class="btn btn-primary btn-sm" href="{{ route('installments.show', $installment->id) }}">查看</a></td>
            </tr>
          @endforeach
          </tbody>
        </table>
        <div class="float-right">{{ $installments->render() }}</div>
      </div>
    </div>
  </div>

</div>

@endsection

