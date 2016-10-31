@extends('layouts.admin')

@section('body')
    <div class="panel panel-default">
        <div class="panel-heading">
            <ol class="breadcrumb">
                <li>用户</li>
                <li class="active">修改信息</li>
            </ol>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    @if(Session::has('flash_message_success'))
                        <div class="alert alert-success">{{ Session::get('flash_message_success') }}</div>
                    @endif
                    <h1 class="title text-lighter">
                        用户信息
                    </h1>
                    <hr>
                    <div class="well">
                        {!! Form::model(Auth::user(), ['url' => 'user/profile', 'method' => 'patch']) !!}
                        <div class="form-group {{ has_error_class($errors, 'name') }}">
                            {!! Form::label('name', '用户名', ['class' => 'control-label']) !!}
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            {!! error_block($errors, 'name') !!}
                        </div>

                        <div class="form-group {{ has_error_class($errors, 'email') }}">
                            {!! Form::label('email', '邮箱', ['class' => 'control-label']) !!}
                            {!! Form::email('email', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            {!! error_block($errors, 'email') !!}
                        </div>

                        <div class="form-group {{ has_error_class($errors, 'old_password') }}">
                            {!! Form::label('old_password', '原始密码', ['class' => 'control-label']) !!}
                            {!! Form::password('old_password', ['class' => 'form-control']) !!}
                            {!! error_block($errors, 'old_password') !!}
                        </div>

                        <div class="form-group {{ has_error_class($errors, 'new_password') }}">
                            {!! Form::label('new_password', '新密码(留空表示不修改密码)', ['class' => 'control-label']) !!}
                            {!! Form::password('new_password', ['class' => 'form-control']) !!}
                            {!! error_block($errors, 'new_password') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('new_password_confirmation', '重复新密码', ['class' => 'control-label']) !!}
                            {!! Form::password('new_password_confirmation', ['class' => 'form-control']) !!}
                        </div>

                        <div class="buttons text-right">
                            <a class="btn btn-default" href="/admin/admins">取消</a>
                            <button class="btn btn-primary" type="submit">更新</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection