@extends('adminlte::page')

@section('title', 'ユーザー管理')

@section('content_header')
    <h1>ユーザー管理</h1>
@stop

@section('content')
    <div class="row">
        <!-- フラッシュメッセージを表示：アカウント編集完了時 -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show w-100 text-center" role="alert">
                <i class="fas fa-check-circle" aria-hidden="true"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">アカウント一覧</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <!-- 削除済みを 表示 / 非表示 切り替えボタン-->
                            <div class="input-group-append">
                                @if(Request::is('users'))
                                    <a href="{{ route('deleted.users.show') }}" class="btn btn-outline-secondary rounded">削除済み表示</a>
                                @elseif(Request::is('users/show-deleted'))
                                    <a href="{{ route('users.table') }}" class="btn btn-outline-secondary rounded">削除済み非表示</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap table-sm">
                        <thead>
                            <tr>
                                <th class="text-right">ID</th>
                                <th class="text-left">名前</th>
                                <th class="text-left">メールアドレス</th>
                                <th class="text-center">権限</th>
                                <th class="text-center">登録日時</th>
                                <th class="text-center">最終更新日時</th>                                
                                <th class="text-center">アカウント編集</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="{{ $user->is_deleted ? '' : 'text-muted font-weight-bold' }}">
                                    <td class="text-right">{{ $user->id }}</td>
                                    <td class="text-left">{{ $user->name }}</td>
                                    <td class="text-left">{{ $user->email }}</td>
                                    <td class="text-center">{{ $user->is_admin == 1 ? '管理者' : '一般ユーザー' }}</td>
                                    <td class="text-center">{{ $user->created_at }}</td>
                                    <td class="text-center">{{ $user->updated_at }}</td>
                                    <td class="text-center">
                                        @if($user->is_deleted === 0)
                                            <button type="button" class="btn btn-outline-primary btn-sm" disabled aria-label="アカウント編集ボタン" aria-disabled="true">
                                            <i class="fa fa-pen"></i>
                                            </button>
                                        @else
                                            <a class="btn btn-outline-primary btn-sm" href="{{ route('user.update', $user->id) }}" role="button" aria-label="アカウント編集フォームへ遷移">
                                                <i class="fa fa-pen"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->is_deleted === 0)
                                            <a class="btn btn-success btn-sm" href="{{ route('user.restore', $user->id) }}">復元</a>
                                        @endif
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