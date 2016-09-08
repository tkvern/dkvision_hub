@extends('layouts.admin')

@section('body')
<div class="panel panel-default">
    <div class="panel-heading">
      <ol class="breadcrumb">
        <li><a href="/home">控制台</a></li>
        <li><a href="/task">任务管理</a></li>
        <li class="active">添加任务</li>
      </ol>
    </div>

    <div class="panel-body">
      <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
          {{ csrf_field() }}

          <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label for="name" class="col-md-3 control-label">名称</label>

              <div class="col-md-8">
                  <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
              </div>
          </div>

          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-md-3 control-label">视频路径</label>

              <div class="col-md-8">
                  <input id="email" type="text" class="form-control" name="email" placeholder="example: ip/your/path" required>
              </div>
          </div>

          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-md-3 control-label">开始桢</label>

              <div class="col-md-8">
                  <input id="email" type="number" class="form-control" name="email" value="0" required>
              </div>
          </div>

          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-md-3 control-label">结束桢</label>

              <div class="col-md-8">
                  <input id="email" type="number" class="form-control" name="email" value="0" required>
              </div>
          </div>

          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-md-3 control-label">时间同步</label>

              <div class="col-md-8 form-group">
                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">00</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>
                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">01</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>
                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">02</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>
                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">03</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>

                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">04</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>
                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">05</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>
                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">06</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>
                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">07</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>

                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">08</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>
                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">09</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>
                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">10</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>
                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">11</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>

                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">12</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>
                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">13</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>
                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">14</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>
                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">15</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>

                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">16</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>
                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">17</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>
                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">18</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>
                  <div class="col-md-3 margin-bottom">
                    <div class="input-group input-group-sm">
                      <div class="input-group-addon">19</div>
                      <input class="form-control" type="number" value="0">
                    </div>
                  </div>
              </div>

          </div>

          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-md-3 control-label">顶部</label>

              <div class="col-md-8">
                  <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" checked> 关闭
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> 开启
                  </label>
              </div>
          </div>

          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-md-3 control-label">底部</label>

              <div class="col-md-8">
                  <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions2" id="inlineRadio1" value="option1" checked> 关闭
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions2" id="inlineRadio2" value="option2"> 开启
                  </label>
              </div>
          </div>

          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-md-3 control-label">分辨率</label>
              <div class="col-md-8">
                  <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions3" id="inlineRadio1" value="option1" checked> 8K
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions3" id="inlineRadio2" value="option2"> 6K
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="inlineRadioOptions3" id="inlineRadio2" value="option2"> 4K
                  </label>
              </div>
          </div>

          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-md-3 control-label">类型</label>
              <div class="col-md-8">
                <label class="checkbox-inline">
                  <input type="checkbox" id="inlineCheckbox1" value="option1" checked> 预览
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" id="inlineCheckbox2" value="option2"> 2D
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" id="inlineCheckbox3" value="option3"> 3D
                </label>
              </div>
          </div>


          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-md-3 control-label">描述</label>

              <div class="col-md-8">
                  <textarea class="form-control" rows="3"></textarea>
                  <!-- <input id="email" type="number" class="form-control" name="email" value="0" required> -->
              </div>
          </div>

          <div class="form-group">
              <div class="col-md-8 col-md-offset-3">
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
