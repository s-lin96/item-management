@extends('adminlte::page')

@section('title', '商品一覧')

@section('content_header')
    <h1>商品一覧</h1>
@stop

@section('content')
    <div class="row">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">商品一覧</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <div class="input-group-append">
                                <a href="{{ route('item.create') }}" class="btn btn-primary">商品登録</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th class="text-right">ID</th>
                                <th class="text-left">種別</th>
                                <th class="text-left">名前</th>
                                <th class="text-right">在庫数</th>
                                <th class="text-right">在庫状況</th>
                                <th class="text-center">最終更新日時</th>
                                <th class="text-center">最終更新者</th>
                                <th class="text-center">入出庫記録</th>
                                <th class="text-center">商品編集</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td class="text-right">{{ $item->id }}</td>
                                    <td class="text-left">{{ $types[$item->type] }}</td>
                                    <td class="text-left">{{ $item->name }}</td>
                                    <td class="text-right">{{ number_format($item->stock) }}</td>
                                    <td class="text-right">
                                        @if($item->stock_status === 1)
                                            <p>十分</p>
                                        @elseif($item->stock_status === 2)
                                            <p><u>少なめ</u></p>
                                        @else
                                            <p><strong>✕ 不足</strong></p>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->updated_at }}</td>
                                    <td class="text-center">{{ $item->user->name }}</td>
                                    <td class="text-center">
                                        <a class="btn btn-warning" href="#" role="button">記録</a>
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-secondary" href="#" role="button">編集</a>
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

@section('css')
@stop

@section('js')
@stop
