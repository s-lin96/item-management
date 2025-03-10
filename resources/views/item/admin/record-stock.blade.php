@extends('adminlte::page')

@section('title', '入出庫記録')

@section('content_header')
    <h1>商品ID: {{ $item->id }} の入出庫記録</h1>
    <p>
        <small>
            <i class="fa fa-info-circle" aria-hidden="true"></i>
            現在庫: {{ $item->stock }} / 安全在庫: {{ $item->safe_stock }}
        </small>
    </p>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-10">
            <!-- フラッシュメッセージを表示：エラー発生時 -->
            @if(count($errors) > 0)
                <div class="alert alert-danger alert-dismissible fade show mx-auto text-center" role="alert">
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> 記録に失敗しました。入力欄のエラーををご確認ください。
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card card-primary">
                <!-- 入出庫記録フォーム -->
                <form action="{{ route('stock.update', $item->id ) }}" method="POST" novalidate>
                    @csrf
                    <div class="card-body">
                        <div class="form-group col-md-5 mx-4">
                            <label>
                                入庫 / 出庫
                            <span class="badge border border-danger text-danger">必須</span>
                            </label>
                            <div class="d-flex">
                                <div class="form-check">
                                    <label>
                                        <input class="form-check-input" type="radio" name="recordType" id="recordType" value="incoming" @if(old('recordType')=='incoming') checked @endif>入庫
                                    </label>
                                </div>
                                <div class="form-check ml-5">
                                    <label>
                                        <input class="form-check-input" type="radio" name="recordType" id="recordType" value="outgoing" @if(old('recordType')=='outgoing') checked @endif>出庫
                                    </label>                             
                                </div>
                            </div>
                            <!-- エラーの詳細を表示 -->
                            <div class="text-danger">
                                @if($errors->has('recordType'))
                                    {{ $errors->first('recordType') }}<br>
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-md-5 mx-4">
                            <label for="quantity">
                                数量
                                <span class="badge border border-danger text-danger">必須</span>
                            </label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}" placeholder="1~4桁の数字を入力してください" min="0" max="9999" required>
                            <!-- エラーの詳細を表示 -->
                            <div class="text-danger">
                                @if($errors->has('quantity'))
                                    {{ $errors->first('quantity') }}<br>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 記録ボタン &
                        戻るボタン：
                        検索条件あれば検索結果一覧へ 
                        検索条件なければデフォルトの商品一覧へ
                    -->
                    <div class="card-footer d-flex">
                            <button type="submit" class="btn btn-primary col-2 mx-2">記録</button>
                            <a  href="{{ session('searchKeyword') || session('searchType') || session('searchStockStatus') 
                            ? route('search.by.admin', [
                                    'keyword' => session('searchKeyword'),
                                    'type' => session('searchType'),
                                    'stockStatus' => session('searchStockStatus')
                                ])
                            : route('items.table') }}" 
                            class="btn btn-secondary col-2 mx-2">
                                戻る
                            </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop