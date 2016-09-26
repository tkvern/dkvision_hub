@extends('layouts.admin')

@section('body')
    <div class="panel panel-default">
        <div class="panel-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">控制台</a></li>
                <li><a href="{{ url('/tasks') }}">任务管理</a></li>
                <li class="active">任务详情</li>
            </ol>
        </div>

        <div class="panel-body">
            <div class="row">
            <dl class="col-md-offset-1 col-md-10">
                <dt>基本信息</dt>
                <dd>
                    <div class="row">
                        <div class="col-md-4">任务编号: {{ $task->id }}</div>
                        <div class="col-md-4">任务名: {{ $task->title }}</div>
                        <div class="col-md-4">创建时间: {{ $task->created_at }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">任务类型: {{ $task->payload['task_type'] }}</div>
                        <div class="col-md-4">UUID: {{ $task->uuid }}</div>
                        <div class="col-md-4">更新时间: {{ $task->updated_at }}</div>
                    </div>
                </dd>
                <br/>
                <dt>参数信息</dt>
                <dd>
                    <div class="row">
                        <div class="col-md-12">视频目录: {{ $task->payload['video_dir'] }}</div>
                        <div class="col-md-4">起始帧: {{ $task->payload['start_frame'] }} - {{ $task->payload['end_frame'] }}</div>
                        <div class="col-md-8">帧同步: {{ implode(', ', $task->payload['time_alignment']) }}</div>
                        <div class="col-md-4">顶部: {{ $task->payload['enable_top'] }}</div>
                        <div class="col-md-4">底部: {{ $task->payload['enable_bottom'] }}</div>
                        <div class="col-md-4">颜色调整: {{ $task->payload['enable_coloradjust'] }}</div>
                        <div class="col-md-4">质量: {{ $task->payload['quality'] }}</div>
                    </div>
                </dd>
                <br/>
                <dt>子任务信息</dt>
                <dd></dd>
            </dl>
            </div>
        </div>
    </div>
@endsection
