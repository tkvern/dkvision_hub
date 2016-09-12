@extends('layouts.admin')

@section('body')
    <div class="panel panel-default">
        <div class="panel-heading">
            <ol class="breadcrumb">
                <li><a href="/home">控制台</a></li>
                <li class="active">任务管理</li>
            </ol>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ url('/tasks/create') }}" class="btn btn-default" title="添加">
                        <span class="glyphicon glyphicon-plus"></span>
                    </a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>名称</th>
                                <th>类型</th>
                                <th>进度</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    <td class="ellipsis">{{ $task->id }}</td>
                                    <td class="ellipsis"> {{ $task->title }} </td>
                                    <td class="ellipsis"> {{ $task->payload['task_type'] }} </td>
                                    <td>
                                        <div class="progress" style="min-width: 200px; margin-bottom: 0px;">
                                            <div class="progress-bar progress-bar-{{ $task->status_class() }}" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 30%;">
                                                30%
                                            </div>
                                        </div>
                                    </td>
                                    <td>{!! $task->status_label() !!}</td>
                                    <td>
                                        <div class="btn-group btn-group-xs btn-group-justified">
                                            <a href="#" class="btn btn-default" role="button">详情</a>
                                            <div class="btn-group btn-group-xs">
                                                <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                    更多 <span class="caret"></span>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    <li><a href="#">删除</a></li>
                                                    <li><a href="#">停止</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pull-right">
                            {{ $tasks->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
