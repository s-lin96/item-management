@extends('adminlte::page')

@section('title', '商品詳細')

@section('content_header')
    <h1>商品ID: {{ $item->id }} の詳細</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-10">
            <div class="card card-primary">
                <!-- 商品詳細セクション -->
                <div class="card-body">
                    <section>
                        <h4 class="mb-3">基本情報</h4>
                            <div class="col-md-6 mx-4">
                                <label>種別</label>
                                <p class="card-text ml-4 bg-light border p-2">{{ $types[$item->type] }}</p>
                            </div>

                            <div class="col-md-6 mx-4">
                                <label>名前</label>
                                <p class="card-text ml-4 bg-light border p-2">{{ $item->name }}</p>
                            </div>
                    </section>
                    <hr>
                    <section>
                        <h4 class="mb-3">在庫情報</h4>
                            <div class="d-flex">
                                <div class="col-md-2 mx-4">
                                    <label>在庫数</label>
                                    <p class="card-text text-right bg-light ml-4 border p-2">{{ number_format($item->stock) }}</p>
                                </div>

                                <div class="col-md-2 mx-4">
                                    <label>単位</label>
                                    <p class="card-text text-right bg-light ml-4 border p-2">{{ $units[$item->unit] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2 mx-4 mt-4">
                                <label>安全在庫数</label>
                                <p class="card-text text-right ml-4 bg-light border p-2">{{ $item->safe_stock }}</p>
                            </div>
                    </section>
                    <hr>
                    <section>
                        <h4 class="mb-3">その他</h4>
                            <div class="col-md-10 mx-4">
                                <label>詳細</label>
                                <p class="card-text text-break ml-4 bg-light border p-2" style="min-height: 200px;">{!! nl2br(e($item->detail)) !!}</p>
                            </div>
                    </section>
                </div>

                <div class="card-footer">
                    <!-- 戻るボタン: 
                        検索条件あれば検索結果一覧へ 
                        検索条件なければデフォルトの商品一覧へ
                    -->
                    <a  href="{{ session('searchKeyword') || session('searchType') || session('searchStockStatus')
                    ? route('search.by.allusers', [
                            'keyword' => session('searchKeyword'),
                            'type' => session('searchType'),
                            'stockStatus' => session('searchStockStatus')
                        ])
                    : route('items.table.readonly') }}" 
                    class="btn btn-outline-secondary col-2" role="button">
                        戻る
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop