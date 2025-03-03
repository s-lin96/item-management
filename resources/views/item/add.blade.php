@extends('adminlte::page')

@section('title', '商品登録')

@section('content_header')
    <h1>商品登録</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-10">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <h4 class="alert-heading">登録に失敗しました</h4>
                    <p>以下の項目をご確認ください</p>
                    <hr>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card card-primary">
                <form action="{{ route('item.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <section>
                            <h2>基本情報</h2>
                                <div class="form-group">
                                    <label for="type">種別</label>
                                    <select name="type" id="type" required>
                                        <option value="">-- 選択してください --</option>
                                        @foreach(config('types.types') as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="name">名前</label>
                                    <input type="text" class="form-control" id="name" name="name" max="100" required>
                                </div>
                        </section>
                        <hr>
                        <section>
                            <h2>在庫情報</h2>
                                <div class="form-group">
                                    <label for="stock">在庫数</label>
                                    <input type="number" class="form-control" id="stock" name="stock" min="0" max="9999" required>
                                </div>

                                <div class="form-group">
                                    <label for="unit">単位</label>
                                    <select name="unit" id="unit" required>
                                        <option value="">-- 選択してください --</option>
                                        @foreach(config('units.units') as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="safe_stock">安定在庫数</label>
                                    <input type="number" class="form-control" id="safe_stock" name="safe_stock" min="0" max="999" required>
                                </div>
                        </section>
                        <hr>
                        <section>
                            <h2>詳細情報</h2>
                                <div class="form-group">
                                    <label for="detail">説明</label>
                                    <input type="text" class="form-control" id="detail" name="detail" max="500" required>
                                </div>
                        </section>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">登録</button>
                        <a class="btn btn-secondary" href="{{ route('items.table') }}">キャンセル</a>
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
