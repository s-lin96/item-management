@extends('adminlte::page')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="text-center">
        <h1 class="display-1 mb-2">
            <i class="fa fa-exclamation-circle" aria-hidden="true"></i> 404 Error
        </h1>
        <p class="lead">アクセスしようとしたページが見つかりませんでした。</p>
        <p class="text-left">以下のような原因が考えられます：</p>
        <ul class="text-left">
            <li>アクセスしようとしたページが存在しない。</li>
            <li>URLアドレスが間違っている。</li>
        </ul>
        <a href="{{ route('home') }}" class="btn btn-primary" role="button">ホームへ</a>
    </div>
</div>
@stop

@section('css')
@stop

@section('js')
@stop