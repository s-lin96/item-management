@extends('adminlte::page')

@section('title', '商品一覧')

@section('content_header')
    <h1>商品一覧</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <!-- 検索フォーム -->
                    <form action="{{ route('search.by.allusers') }}" method="GET"  class="form-inline">
                        <div class="form-row align-items-center">
                            <!-- 種別を選択 -->
                            <div class="col-auto"> 
                                <label class="sr-only" for="type">種別</label>
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-tags" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    <select class="form-control" name="type" id="type">
                                        <option value="">-- 種別 --</option>
                                        @foreach(config('types.types') as $id => $name)
                                            <option value="{{ $id }}" {{ request('type') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- キーワードを入力 -->
                            <div class="col-auto">
                                <label class="sr-only" for="keyword">商品名または商品詳細</label>
                                <input type="text" class="form-control mb-2 mr-sm-2" id="keyword" name="keyword" value="{{ $cleanedKeyword ?? '' }}" placeholder="商品名 または 商品詳細" >
                            </div>

                            <!-- 検索ボタン -->
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary mb-2">検索</button>
                            </div>
                        </div>
                    </form>
                    <!-- 検索条件リセットボタン -->
                    <div class="col-auto">
                        <a href="{{ route('items.table.readonly') }}" class="text-muted">
                            <small>
                                <i class="fa fa-times-circle" aria-hidden="true"></i>
                                条件クリア
                            </small>
                        </a>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <!-- 商品一覧 -->
                    @if($items->count() > 0)
                        <table class="table table-hover text-nowrap table-sm">
                            <thead>
                                <tr>
                                    <th class="text-right">ID</th>
                                    <th class="text-left">種別</th>
                                    <th class="text-left">名前</th>
                                    <th class="text-right">在庫数</th>
                                    <th class="text-center">在庫状況</th>
                                    <th class="text-center">最終更新日時</th>
                                    <th class="text-center">最終更新者</th>
                                    <th class="text-center">商品詳細</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="text-right">{{ $item->id }}</td>
                                        <td class="text-left">{{ $types[$item->type] ?? '不明' }}</td>
                                        <td class="text-left">{{ $item->name }}</td>
                                        <td class="text-right">{{ number_format($item->stock) }}</td>
                                        <td class="text-center">
                                            @if($item->stock_status === 1)
                                                <span class="badge badge-light">十 分</span>
                                            @elseif($item->stock_status === 2)
                                                <span class="badge badge-warning">少なめ</span>
                                            @else
                                                <span class="badge badge-danger">不 足</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->updated_at }}</td>
                                        <td class="text-center">{{ $item->user->name }}</td>
                                        <td class="text-center">
                                            <a class="btn btn-outline-primary btn-sm" href="{{ route('item.detail', $item->id) }}" role="button" aria-label="商品詳細画面へ遷移">
                                                <i class="fa fa-search-plus"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>

                        <!-- ページネーションリンク -->
                        <div class="card-footer d-flex justify-content-end">
                            {{ $items->appends(request()->query())->links() }}
                        </div>
                    @else
                        <p class="text-center m-3 font-weight-bold">商品が見つかりませんでした。</p>
                    @endif
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
