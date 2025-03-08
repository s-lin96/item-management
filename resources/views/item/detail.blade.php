@extends('adminlte::page')

@section('title', '商品詳細')

@section('content_header')
    <h1>商品ID: {{ $item->id }} の詳細</h1>
@stop

@section('content')
    <div class="row">
        <!-- フラッシュメッセージを表示：その他のエラー -->
        @if(session('failure'))
            <div class="alert alert-danger alert-dismissible fade show mx-auto text-center" role="alert">
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{ session('failure') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
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
                                    <p class="card-text text-right bg-light ml-4 border p-2">{{ $item->stock }}</p>
                                </div>

                                <div class="col-md-2 mx-4">
                                    <label>単位</label>
                                    <p class="card-text text-right bg-light ml-4 border p-2">{{ $units[$item->unit] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2 mx-4 mt-4">
                                <label>安定在庫数</label>
                                <p class="card-text text-right ml-4 bg-light border p-2">{{ $item->safe_stock }}</p>
                            </div>
                    </section>
                    <hr>
                    <section>
                        <h4 class="mb-3">詳細情報</h4>
                            <div class="col-md-10 mx-4">
                                <label>説明</label>
                                <p class="card-text text-break ml-4 bg-light border p-2" style="min-height: 200px;">{!! nl2br(e($item->detail)) !!}</p>
                            </div>
                    </section>
                </div>

                <!-- 戻る -->
                <div class="card-footer">
                    <a class="btn btn-secondary col-2 mx-2" href="{{ route('items.table.readonly') }}">戻る</a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop