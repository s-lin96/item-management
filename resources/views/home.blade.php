@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<!-- 在庫状況お知らせ欄 -->
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
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
