@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">功能导航</div>
                <ul class="list-group">
                  <a href="/home" class="list-group-item">控制台</a>
                  <a href="/task" class="list-group-item">任务管理</a>
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            @yield('body')
        </div>
    </div>
</div>
@endsection
