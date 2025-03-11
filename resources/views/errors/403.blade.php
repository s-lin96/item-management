@extends('adminlte::page')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="text-center">
        <h1 class="display-1 mb-2">
            <i class="fa fa-exclamation-circle" aria-hidden="true"></i> 403 Error
        </h1>
        <p class="lead">この操作は許可されていません。</p>
        <a href="{{ route('home') }}" class="btn btn-primary" role="button">ホームへ</a>
    </div>
</div>
@stop

@section('css')
@stop

@section('js')
@stop