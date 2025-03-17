@extends('adminlte::page')

@section('title', 'ホーム')

@section('content_header')
    <h1>ホーム</h1>
@stop

@section('content')
<!-- 在庫状況お知らせ欄: 不足 & 少なめ -->
<div class="d-flex">
    <div class="small-box bg-danger w-75 mx-1">
        <div class="inner">
            <h3>{{ $insufficientStockCount }} <small>件</small></h3>
            <p>在庫不足</p>
        </div>
        <div class="icon">
            <i class="fa fa-minus-circle"></i>
        </div>
        @can('isAdmin')
            <a href="{{ route('search.by.admin', ['stockStatus' => 'insufficientStock']) }}" class="small-box-footer">
                もっと詳しく <i class="fa fa-search-plus"></i>
            </a>
        @else
            <a href="{{ route('search.by.allusers', ['stockStatus' => 'insufficientStock']) }}" class="small-box-footer">
                もっと詳しく <i class="fa fa-search-plus"></i>
            </a>
        @endcan
    </div>

    <div class="small-box bg-warning w-75 mx-1">
        <div class="inner">
            <h3>{{ $lowStockCount }} <small>件</small></h3>
            <p>在庫少なめ</p>
        </div>
        <div class="icon">
            <i class="fa fa-exclamation-circle"></i>
        </div>
        @can('isAdmin')
            <a href="{{ route('search.by.admin', ['stockStatus' => 'lowStock']) }}" class="small-box-footer">
                もっと詳しく <i class="fa fa-search-plus"></i>
            </a>
        @else
            <a href="{{ route('search.by.allusers', ['stockStatus' => 'lowStock']) }}" class="small-box-footer">
                もっと詳しく <i class="fa fa-search-plus"></i>
            </a>
        @endcan
    </div>
</div>

<div class="d-flex">
<!-- 削除済み商品表示欄 -->
<div class="card w-75 mx-1" style="height: 420px;">
    <div class="card-header bg-light">
        <h3 class="card-title">
            <i class="fa fa-trash" aria-hidden="true"></i> 削除された商品 
        </h3>
    </div>
    <div class="card-body overflow-auto" style="max-height: calc(100% - 56px);">
        <ul class="list-group list-group-flush">
            @foreach($deletedItems as $deletedItem)
                <li class="list-group-item text-muted">{{ $deletedItem['name'] }}</li>
            @endforeach
        </ul>
    </div>
</div>
<!-- クイックアクセス: 商品種別 -->
<div class="card w-75 mx-1" style="height: 420px;">
    <div class="card-header bg-light">
        <h3 class="card-title">
            <i class="fa fa-tags" aria-hidden="true"></i> 商品種別
            <small class="text-muted">クイックアクセス</small>
        </h3>
    </div>
    <div class="card-body overflow-auto" style="max-height: calc(100% - 56px);">
        <ul class="list-group list-group-flush">
            @foreach(config('types.types') as $id => $name)
                <li class="list-group-item list-group-item-action">
                    <a href="{{ route('search.by.allusers', ['type' => $id]) }}" class="card-link">
                        {{ $name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
</div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
