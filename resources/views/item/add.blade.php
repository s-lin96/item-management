@extends('adminlte::page')

@section('title', '商品登録')

@section('content_header')
    <h1>商品登録</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-10">
            @if(count($errors) > 0)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <p>商品の登録ができませんでした。<br>入力内容をご確認ください。</p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card card-primary">
                <form action="{{ route('item.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="card-body">
                        <section>
                            <h2>基本情報</h2>
                                <div class="form-group">
                                    <label for="type">種別</label>
                                    <select name="type" id="type" required>
                                        <option value="">-- 選択してください --</option>
                                        @foreach(config('types.types') as $id => $name)
                                            <option value="{{ $id }}" {{ old('type') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">
                                        @if($errors->has('type'))
                                            {{ $errors->first('type') }}<br>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name">名前</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"  max="100" required>
                                    <div class="text-danger">
                                        @if($errors->has('name'))
                                            {{ $errors->first('name') }}<br>
                                        @endif
                                    </div>
                                </div>
                        </section>
                        <hr>
                        <section>
                            <h2>在庫情報</h2>
                                <div class="form-group">
                                    <label for="stock">在庫数</label>
                                    <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock') }}" min="0" max="9999" required>
                                    <div class="text-danger">
                                        @if($errors->has('stock'))
                                            {{ $errors->first('stock') }}<br>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="unit">単位</label>
                                    <select name="unit" id="unit" required>
                                        <option value="">-- 選択してください --</option>
                                        @foreach(config('units.units') as $id => $name)
                                            <option value="{{ $id }}" {{ old('unit') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">
                                        @if($errors->has('unit'))
                                            {{ $errors->first('unit') }}<br>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="safe_stock">安定在庫数</label>
                                    <input type="number" class="form-control" id="safe_stock" name="safe_stock" value="{{ old('safe_stock') }}" min="0" max="999" required>
                                    <div class="text-danger">
                                        @if($errors->has('safe_stock'))
                                            {{ $errors->first('safe_stock') }}<br>
                                        @endif
                                    </div>
                                </div>
                        </section>
                        <hr>
                        <section>
                            <h2>詳細情報</h2>
                                <div class="form-group">
                                    <label for="detail">説明</label>
                                    <input type="text" class="form-control" id="detail" name="detail" value="{{ old('detail') }}" max="500" required>
                                    <div class="text-danger">
                                        @if($errors->has('detail'))
                                            {{ $errors->first('detail') }}<br>
                                        @endif
                                    </div>
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
