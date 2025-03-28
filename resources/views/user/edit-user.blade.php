@extends('adminlte::page')

@section('title', 'アカウント編集')

@section('content_header')
    <h1>ユーザーID: {{ $user->id }} の編集</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-10">
            <!-- フラッシュメッセージを表示：バリデーションエラー -->
            @if(count($errors) > 0)
                <div class="alert alert-danger alert-dismissible fade show mx-auto text-center" role="alert">
                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> 更新に失敗しました。入力欄のエラーをご確認ください。
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card card-primary">
                <!-- アカウント編集フォーム -->
                <form action="{{ route('user.update', $user->id ) }}" method="POST" novalidate>
                    @csrf
                    <div class="card-body">
                        <div class="form-group mx-4">
                            <label for="name">
                                名前
                                <span class="badge border border-danger text-danger">必須</span>
                            </label>
                            <input type="text" class="form-control col-md-5" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="1~100文字で入力してください" max="100" required>
                            <!-- エラーの詳細を表示 -->
                            <div class="text-danger">
                                @if($errors->has('name'))
                                    {{ $errors->first('name') }}<br>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mx-4">
                            <label for="email">
                                メールアドレス
                                <span class="badge border border-danger text-danger">必須</span>
                            </label>
                            <input type="email" class="form-control col-md-5" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="@ドメインを含んだ形式で入力してください" max="255" required>
                            <!-- エラーの詳細を表示 -->
                            <div class="text-danger">
                                @if($errors->has('email'))
                                    {{ $errors->first('email') }}<br>
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-md-5 mx-4 mb-5">
                            <label>
                                権限
                            <span class="badge border border-danger text-danger">必須</span>
                            </label>
                            <div class="d-flex">
                                <div class="form-check">
                                    <label>
                                        <input class="form-check-input" type="radio" name="isAdmin" id="isAdmin" value="1" {{ old('isAdmin', $user->is_admin)==1 ? 'checked' : '' }}>管理者
                                    </label>
                                </div>
                                <div class="form-check ml-5">
                                    <label>
                                        <input class="form-check-input" type="radio" name="isAdmin" id="isAdmin" value="0" {{ old('isAdmin', $user->is_admin)==0 ? 'checked' : '' }}>一般ユーザー
                                    </label>                             
                                </div>
                            </div>
                            <!-- エラーの詳細を表示 -->
                            <div class="text-danger">
                                @if($errors->has('isAdmin'))
                                    {{ $errors->first('isAdmin') }}<br>
                                @endif
                            </div>
                        </div>

                        <!-- 削除ボタン -->
                        <a class="btn btn-danger btn-sm col-1 mt-5" href="{{ route('user.delete', $user->id) }}">
                            削除 <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>
                    </div>

                    <!-- 戻る & 更新ボタン -->
                    <div class="card-footer d-flex justify-content-between">
                        <a class="btn btn-outline-secondary col-2" href="{{ route('users.table') }}" role="button">戻る</a>
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