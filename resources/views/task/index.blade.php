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
                        <span class="glyphicon glyphicon-plus">添加任务</span>
                    </a>
                    <div class="pull-right">
                        <form class="form-inline" 
                                id="filterForm" 
                                role="form" 
                                action="/tasks" 
                                method="get">
                            <div class="form-group">
                                <label for="status" class="sr-only">任务状态</label>
                                <select class="form-control" name="all">
                                    <option value="no" 
                                            {{ option_seleted(!is_true($filterKeys['all'])) }}
                                    >
                                        我的任务
                                    </option>
                                    <option value="yes" 
                                            {{ option_seleted(is_true($filterKeys['all'])) }}
                                    >
                                        全部任务
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status" class="sr-only">任务状态</label>
                                <select class="form-control" name="status">
                                    <option value="">所有状态</option>
                                    @foreach(App\Task::$ALL_STATUS as $type => $value)
                                    <option value="{{ $type }}"
                                            {{ option_seleted("$type" === $filterKeys['status']) }}>
                                    {{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="key" class="sr-only">key</label>
                                <input class="form-control" 
                                        type="text" 
                                        name="key"
                                        value="{{ $filterKeys['key'] }}" 
                                        placeholder="输入任务名进行搜索">
                            </div>
                            <button type="submit" class="btn btn-default">Go</button>
                        </form>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <!-- <th>UUID</th> -->
                                <th>名称</th>
                                <th>类型</th>
                                <th>创建人</th>
                                <th>执行机器IP</th>
                                <th>进度</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    <!-- <td class="ellipsis"
                                            data-toggle="tooltip" data-placement="left" title="{{ $task->uuid }}">
                                        {{ str_limit($task->uuid, 8, '') }}
                                    </td> -->
                                    <td class="ellipsis"> {{ $task->title }} </td>
                                    <td class="ellipsis"> {{ $task->payload['task_type'] }} </td>
                                    <td class="ellipsis"> {{ $task->creator->name }} </td>
                                    <td class="ellipsis"> {{ $task->exec_ip }} </td>
                                    <td>
                                        <div class="progress" style="min-width: 200px; margin-bottom: 0px;">
                                            <div class="progress-bar progress-bar-{{ $task->status_class() }}" role="progressbar"
                                                 aria-valuenow="{{ $task->processing() }}" aria-valuemin="0" aria-valuemax="100"
                                                 style="width: {{ $task->processing() }}%;">
                                                {{ $task->processing() }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>{!! $task->status_label() !!}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ url('tasks', [$task->id]) }}" class="btn btn-default" role="button">详情</a>
                                            <div class="btn-group btn-group-sm" id="more">
                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                    更多
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    @if($task->canRetry())
                                                    <li><a class="retry" href="#" data-target="/tasks/{{ $task->id }}/retry">重试</a></li>
                                                    @endif
                                                    @if($task->canTerminate())
                                                    <li><a class="terminate" href="#" data-target="/tasks/{{ $task->id }}/terminate">终止</a></li>
                                                    @endif
                                                    @if($task->canDelete())
                                                    <li>
                                                        <a class="delete" href="#" data-target="{{ url('/tasks', [$task->id]) }}">删除</a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pull-right">
                            {!! $tasks->appends($filterKeys)->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
<script>
    $(document).ready(function () {
        $('#filterForm select').on('change', function() {
            $('#filterForm').submit();
        });
        $('#filterForm input[name=key]').on('keyup', function(e) {
            if (e.keyCode == 13) {
                $('#filterForm').submit();
            }
        });
        $('[data-toggle=tooltip]').tooltip({container: 'body'});
        // 任务删除
        $('#more a.delete').on('click', function () {
            var url = $(this).data('target');
            swal({
                    title: "",
                    text: "确认删除任务?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    cancelButtonText: "取消",
                    confirmButtonText: "确认",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function(){
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            _method: 'delete'
                        },
                        success: function(data) {
                            if(data.err_code == '0') {
                                window.location.reload();
                            } else {
                                swal("糟糕", data.err_msg, "error");
                            }
                        }
                    })
                });
        });
        // 任务重试
        $('#more a.retry').on('click', function() {
            var url = $(this).data('target');
            swal({
                    title: "",
                    text: "确认重试任务?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    cancelButtonText: "取消",
                    confirmButtonText: "确认",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function(){
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            force:  false
                        },
                        success: function(data) {
                            if(data.err_code == '0') {
                                window.location.reload();
                            } else {
                                swal("糟糕", data.err_msg, "error");
                            }
                        }
                    })
                }
            );
        });
        // 任务终止
        $('#more a.terminate').on('click', function() {
            var url = $(this).data('target');
            swal({
                    title: "",
                    text: "确认终止任务?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    cancelButtonText: "取消",
                    confirmButtonText: "确认",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function(){
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            force:  false
                        },
                        success: function(data) {
                            if(data.err_code == '0') {
                                window.location.reload();
                            } else {
                                swal("糟糕", data.err_msg, "error");
                            }
                        }
                    })
                }
            );
        });
    });
</script>
@endsection
