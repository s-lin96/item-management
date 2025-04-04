@extends('adminlte::page')

@section('title', '商品編集')

@section('content_header')
    <h1>商品ID: {{ $item->id }} の編集</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-10">
            <!-- フラッシュメッセージを表示：バリデーションエラー -->
            @if(count($errors) > 0)
                <div class="alert alert-danger alert-dismissible fade show mx-auto text-center" role="alert">
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> 更新に失敗しました。入力欄のエラーををご確認ください。
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card card-primary">
                <!-- 商品編集フォーム -->
                <form action="{{ route('item.update', $item->id ) }}" method="POST" novalidate>
                    @csrf
                    <div class="card-body">
                        <section>
                            <h4 class="mb-3">基本情報</h4>
                                <div class="form-group mx-4">
                                    <label for="type">
                                        種別
                                        <span class="badge border border-danger text-danger">必須</span>
                                    </label>
                                    <select class="form-control col-md-5" name="type" id="type" required>
                                        <option value="">-- 選択してください --</option>
                                        @foreach(config('types.types') as $id => $name)
                                            <option value="{{ $id }}" @if(old('type', $item->type) == $id) selected @endif>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <!-- エラーの詳細を表示 -->
                                    <div class="text-danger">
                                        @if($errors->has('type'))
                                            {{ $errors->first('type') }}<br>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group mx-4">
                                    <label for="name">
                                        名前
                                        <span class="badge border border-danger text-danger">必須</span>
                                    </label>
                                    <input type="text" class="form-control col-md-5" id="name" name="name" value="{{ old('name', $item->name) }}" placeholder="1~100文字で入力してください" max="100" required>
                                    <!-- エラーの詳細を表示 -->
                                    <div class="text-danger">
                                        @if($errors->has('name'))
                                            {{ $errors->first('name') }}<br>
                                        @endif
                                    </div>
                                </div>
                        </section>
                        <hr>
                        <section>
                            <h4 class="mb-3">在庫情報</h4>
                                <div class="form-row">
                                    <div class="col-md-5 mx-4">
                                        <label for="stock">在庫数</label>
                                        <p class="card-text bg-light border p-2">{{ number_format($item->stock) }}</p>  
                                    </div>

                                    <div class="form-group col-md-5 mx-4">
                                        <label for="unit">
                                            単位
                                            <span class="badge border border-danger text-danger">必須</span>
                                        </label>
                                        <select class="form-control" name="unit" id="unit" required>
                                            <option value="">-- 選択してください --</option>
                                            @foreach(config('units.units') as $id => $name)
                                                <option value="{{ $id }}" @if(old('unit', $item->unit) == $id) selected @endif>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <!-- エラーの詳細を表示 -->
                                        <div class="text-danger">
                                            @if($errors->has('unit'))
                                                {{ $errors->first('unit') }}<br>
                                            @endif
                                        </div>
                                    </div>

                                <div class="form-group col-md-5 mx-4">
                                    <label for="safe_stock">
                                        安全在庫数
                                        <span class="badge border border-danger text-danger">必須</span>
                                    </label>
                                    <input type="text" class="form-control" id="safe_stock" name="safe_stock" value="{{ old('safe_stock', $item->safe_stock) }}" placeholder="1~3桁の半角数字を入力してください" min="1" max="999" required>
                                    <!-- エラーの詳細を表示 -->
                                    <div class="text-danger">
                                        @if($errors->has('safe_stock'))
                                            {{ $errors->first('safe_stock') }}<br>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </section>
                        <hr>
                        <section class="mb-5">
                            <h4 class="mb-3">その他</h4>
                                <div class="form-group mx-4">
                                    <label for="detail">
                                        詳細
                                        <span class="badge border border-danger text-danger">必須</span>
                                    </label>
                                    <textarea class="form-control col-md-10" id="detail" name="detail" placeholder="1~500文字で入力してください" cols="50" rows="10" max="500" required>{{ old('detail', $item->detail) }}</textarea>
                                    <!-- エラーの詳細を表示 -->
                                    <div class="text-danger">
                                        @if($errors->has('detail'))
                                            {{ $errors->first('detail') }}<br>
                                        @endif
                                    </div>
                                </div>
                        </section>
                
                        <!-- 削除ボタン -->
                        <a class="btn btn-danger btn-sm col-1" href="{{ route('item.delete', $item->id) }}" role="button">
                            削除 <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>                        
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <!-- 戻るボタン：
                            検索条件あれば検索結果一覧へ 
                            検索条件なければデフォルトの商品一覧へ
                        -->
                        <a  href="{{ session('searchKeyword') || session('searchType') || session('searchStockStatus') 
                        ? route('search.by.admin', [
                                'keyword' => session('searchKeyword'),
                                'type' => session('searchType'),
                                'stockStatus' => session('searchStockStatus')
                            ])
                        : route('items.table') }}" 
                        class="btn btn-outline-secondary col-2" role="button">
                            戻る
                        </a>

                        <!-- 更新ボタン -->
                        <button type="submit" class="btn btn-primary col-2 ml-auto">更新</button>
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