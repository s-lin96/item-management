@extends('adminlte::page')

@section('title', 'ユーザー管理')

@section('content_header')
    <h1>ユーザー管理</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">ユーザーアカウント一覧</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th class="text-right">ID</th>
                                <th class="text-left">名前</th>
                                <th class="text-left">メールアドレス</th>
                                <th class="text-center">権限</th>
                                <th class="text-center">最終更新日時</th>
                                <th class="text-center">アカウント編集</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="text-right">{{ $user->id }}</td>
                                    <td class="text-left">{{ $user->name }}</td>
                                    <td class="text-left">{{ $user->email }}</td>
                                    <td class="text-center">{{ $user->is_admin == 1 ? '管理者' : '一般ユーザー' }}</td>
                                    <td class="text-center">{{ $user->updated_at }}</td>
                                    <td class="text-center">
                                        <a class="btn btn-outline-secondary" href="{{ route('user.update', $user->id) }}" role="button" aria-label="アカウント編集フォームへ遷移">
                                            <i class="fa fa-pen"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop