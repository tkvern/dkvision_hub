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
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/tasks') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('payload.video_dir') ? ' has-error' : '' }}">
                    <label for="payload_video_dir" class="col-md-2 control-label">视频路径</label>

                    <div class="col-md-8">
                        <input id="payload_video_dir" type="text" class="form-control"
                               name="payload[video_dir]" placeholder="例如: /data/path"
                               value="{{ old('payload.video_dir') }}"
                               autocomplete="off" required autofocus>
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
                        <input id="payload[start_frame]" type="number" class="form-control" name="payload[start_frame]" value="0" required>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('payload.end_frame') ? ' has-error' : '' }}">
                    <label for="payload[end_frame]" class="col-md-2 control-label">结束桢</label>

                    <div class="col-md-8">
                        <input id="payload[end_frame]" type="number" class="form-control" name="payload[end_frame]" value="0" required>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('payload.time_alignment') ? ' has-error' : '' }}">
                    <label for="payload[time_alignment]" class="col-md-2 control-label">时间同步</label>

                    <div class="col-md-8">
                        <textarea class="form-control" id="configtime" rows="8" placeholder="以下滑线分割填写时间同步可自动识别"></textarea>
                        <button class="btn btn-default" type="button" id="autotime">自动识别</button>

                    </div>
                </div>
                <div class="form-group{{ $errors->has('payload.time_alignment') ? ' has-error' : '' }}">
                    <label for="payload[time_alignment]" class="col-md-2 control-label"></label>
                    <div class="col-md-8 form-group">
                        @for($i = 0; $i < 20; $i++)
                            <div class="col-md-3 margin-bottom">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-addon">{{ str_pad($i, 2, 0, STR_PAD_LEFT) }}</div>
                                    <input name="payload[time_alignment][]" class="form-control" type="number" value="0" required>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                <div class="form-group{{ $errors->has('payload.enable_top') ? ' has-error' : '' }}">
                    <label for="payload[enable_top]" class="col-md-2 control-label">顶部</label>

                    <div class="col-md-8">
                        <label class="radio-inline">
                            <input type="radio" name="payload[enable_top]"  value="0" checked> 关闭
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="payload[enable_top]"  value="1"> 开启
                        </label>
                    </div>
                </div>

                <div class="form-group{{ $errors->has('payload.enable_bottom') ? ' has-error' : '' }}">
                    <label for="payload[enable_bottom]" class="col-md-2 control-label">底部</label>

                    <div class="col-md-8">
                        <label class="radio-inline">
                            <input type="radio" name="payload[enable_bottom]" value="0" checked> 关闭
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="payload[enable_bottom]" value="1"> 开启
                        </label>
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
                        <label class="checkbox-inline">
                            <input type="checkbox" name="task_types[]" value="3D" checked> 3D
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="task_types[]" value="3D_Fast"> 3D_Fast
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="task_types[]" value="2D"> 2D
                        </label>
                        <label class="checkbox-inline">
                            <input type="checkbox" name="task_types[]" value="2D_Fast"> 预览
                        </label>
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
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection

@section('page_js')
<script>
    $(document).ready(function () {
        $('#autotime').on('click', function(e) {
            if($('#configtime').val() == "") {
              return;
            }
            var time = $('#configtime').val()
                                        .split(/[,_\n]/)
                                        .filter(function(item) {
                                            return item != ''
                                        })
                                        .map(function(item) {
                                            return item.split(' ')[1].trim();
                                        });
            var alignment = $("input[name='payload[time_alignment][]']");
            alignment.val('0');
            var minLength = time.length > alignment.length ? alignment.length : time.length;
            for(var i = 0; i < minLength; i ++ ) {
              alignment[i].value = time[i];
            }
        });
        (function() {
            var originVal = '';
            $('#payload_video_dir').on('focus', function () {
                originVal = $(this).val();
            })
            $('#payload_video_dir').on('blur', function() {
                if(originVal == $('#title').val() || ($('#title').val() == '' && $('#payload_video_dir').val() != '')) {
                    $('#title').val($('#payload_video_dir').val());
                }
            });
        })();
    });
</script>
@endsection
