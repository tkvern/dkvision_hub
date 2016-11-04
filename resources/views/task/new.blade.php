@extends('layouts.admin')

@section('body')
    <div class="panel panel-default">
        <div class="panel-heading">
            <ol class="breadcrumb">
                <li><a href="{{ url('/home') }}">控制台</a></li>
                <li><a href="{{ url('/tasks') }}">任务管理</a></li>
                <li class="active">添加任务</li>
            </ol>
        </div>

        <div class="panel-body">
           @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/tasks') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('payload.video_dir') ? ' has-error' : '' }}">
                    <label for="payload_video_dir" class="col-md-2 control-label">视频路径</label>

                    <div class="col-md-8">
                        <div class="input-group">
                            <input id="payload_video_dir" type="text" class="form-control"
                                   name="payload[video_dir]" placeholder="例如: /data/path"
                                   value="{{ old('payload.video_dir') }}"
                                   autocomplete="on" required autofocus>
                            <span class="input-group-btn">
                                <button id="windows_dir_reload" class="btn btn-default" type="button">转换路径</button>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('payload.output_dir') ? ' has-error' : '' }}">
                    <label for="payload_output_dir" class="col-md-2 control-label">输出路径</label>

                    <div class="col-md-8">
                        <input id="payload_output_dir" type="text" class="form-control"
                               name="payload[output_dir]" placeholder="例如: /data/path"
                               value="{{ old('payload.output_dir') }}"
                               autocomplete="on" required autofocus>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                    <label for="title" class="col-md-2 control-label">任务名</label>

                    <div class="col-md-8">
                        <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" required autocomplete="off">
                    </div>
                </div>

                <div class="form-group{{ $errors->has('payload.start_frame') ? ' has-error' : '' }}">
                    <label for="payload[start_frame]" class="col-md-2 control-label">开始桢</label>

                    <div class="col-md-8">
                        <input id="payload[start_frame]" type="number" class="form-control" name="payload[start_frame]" value="0" autocomplete="off" required>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('payload.end_frame') ? ' has-error' : '' }}">
                    <label for="payload[end_frame]" class="col-md-2 control-label">结束桢</label>

                    <div class="col-md-8">
                        <input id="payload[end_frame]" type="number" class="form-control" name="payload[end_frame]" value="0" autocomplete="off" required>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('payload.enable_coloradjust') ? ' has-error' : '' }}">
                    <label for="payload[enable_coloradjust]" class="col-md-2 control-label">颜色调整</label>

                    <div class="col-md-8">
                        <label class="radio-inline">
                            <input type="radio" name="payload[enable_coloradjust]" value="0"> 关闭
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="payload[enable_coloradjust]" value="1" checked> 开启
                        </label>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('payload.quality') ? ' has-error' : '' }}">
                    <label for="payload[quality]" class="col-md-2 control-label">质量</label>
                    <div class="col-md-8">
                        <label class="radio-inline">
                            <input type="radio" name="payload[quality]" value="8k" checked> 8K
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="payload[quality]" value="6k"> 6K
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="payload[quality]" value="4k"> 4K
                        </label>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('payload.camera_type') ? ' has-error' : '' }}">
                    <label for="payload[camera_type]" class="col-md-2 control-label">相机类型</label>
                    <div class="col-md-8">
                        <label class="radio-inline">
                            <input type="radio" name="payload[camera_type]" value="GOPRO" checked> GOPRO
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="payload[camera_type]" value="BMPCC"> BMPCC
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="payload[camera_type]" value="AURA"> AURA
                        </label>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('task_types') ? ' has-error' : '' }}">
                    <label for="task_types" class="col-md-2 control-label">任务类型</label>
                    <div class="col-md-8">
                        @foreach(array_except(App\Task::$RENDER_TYPE, ['TOP_BOTTOM']) as $key => $value)
                        <label class="checkbox-inline">
                            <input type="checkbox" name="task_types[]" value="{{$key}}" {{box_checked($key === 'PREVIEW')}}>
                            {{$value}}
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label for="priority" class="col-md-2 control-label">是否紧急</label>
                    <div class="col-md-8">
                        <label class="radio-inline">
                            <input type="radio" name="priority" value="1000"> 是
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="priority" value="100" checked> 否 
                        </label>
                        <span class="help-block">请就任务紧急程度度选择，以免延迟其他任务</span>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                    <label for="description" class="col-md-2 control-label">备注</label>

                    <div class="col-md-8 col-lg-8">
                        <textarea name="description" class="form-control" rows="3"></textarea>
                        <!-- <input id="email" type="number" class="form-control" name="email" value="0" required> -->
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="submit" class="btn btn-primary">
                            提交任务
                        </button>
                        <button type="reset" class="btn btn-default">
                            重置表单
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
   
@endsection

@section('page_js')
<script>
    $(document).ready(function () {
        (function() {
            var originVal = '',
                pre_path = '\/dkvision';
            $('#windows_dir_reload').on('click', function() {
                var payload_video_dir = $('#payload_video_dir').val(),
                    windows_path = new RegExp(/\w:+?\\+?([^\:\?"\<\>\|\.\\\/]+?[\\\b]{0,1})+?/),
                    input_path,
                    output_path;
                if (windows_path.test(payload_video_dir)) {
                    input_path = payload_video_dir.slice(payload_video_dir.indexOf(':') + 1);
                } else {
                    input_path = payload_video_dir;
                }
                input_path = input_path.split(/[\\/]/)
                                       .length > 2 ? input_path
                                       .split(/[\\/]/)
                                       .join('\/') : input_path;

                output_path = input_path.indexOf('data') > -1 ? input_path
                                            .replace('data', 'output') : input_path;

                input_path.indexOf(pre_path) < 0 ? input_path = pre_path + input_path : input_path;
                output_path.indexOf(pre_path) < 0 ? output_path = pre_path + output_path : output_path;

                $('#payload_video_dir').val(input_path);
                $('#payload_output_dir').val(output_path);
            });
        })();
    });
</script>
@endsection
