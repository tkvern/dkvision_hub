@extends('layouts.admin')

@section('body')
<div class="panel panel-default">
    <div class="panel-heading">任务管理</div>

    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <a href="#" class="btn btn-default" title="添加">
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
                          <th>进度</th>
                          <th>状态</th>
                          <th>操作</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="ellipsis">0232312123</td>
                          <td class="ellipsis">
                            wan_ke_yun_cheng_20160208_item1_previewwan_ke_yun_cheng_20160208_item1_previewwan_ke_yun_cheng_20160208_item1_preview
                          </td>
                          <td>
                            <div class="progress" style="min-width: 200px; margin-bottom: 0px;">
                              <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 30%;">
                                30%[20/20009]
                              </div>
                            </div>
                          </td>
                          <td><span class="label label-primary">Waiting</span></td>
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
                        <tr>
                          <td class="ellipsis">0232312123</td>
                          <td class="ellipsis">
                            wan_ke_yun_cheng_20160208_item1_previewwan_ke_yun_cheng_20160208_item1_previewwan_ke_yun_cheng_20160208_item1_preview
                          </td>
                          <td>
                            <div class="progress" style="min-width: 200px; margin-bottom: 0px;">
                              <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 10%;">
                                10%[20/20009]
                              </div>
                            </div>
                          </td>
                          <td><span class="label label-info">working</span></td>
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
                        <tr>
                          <td class="ellipsis">0232312123</td>
                          <td class="ellipsis">
                            wan_ke_yun_cheng_20160208_item1_previewwan_ke_yun_cheng_20160208_item1_previewwan_ke_yun_cheng_20160208_item1_preview
                          </td>
                          <td>
                            <div class="progress" style="min-width: 200px; margin-bottom: 0px;">
                              <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 90%;">
                                90%[20/20009]
                              </div>
                            </div>
                          </td>
                          <td><span class="label label-success">Finish</span></td>
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
                        <tr>
                          <td class="ellipsis">0232312123</td>
                          <td class="ellipsis">
                            wan_ke_yun_cheng_20160208_item1_previewwan_ke_yun_cheng_20160208_item1_previewwan_ke_yun_cheng_20160208_item1_preview
                          </td>
                          <td>
                            <div class="progress" style="min-width: 200px; margin-bottom: 0px;">
                              <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 68%;">
                                68%[20/20009]
                              </div>
                            </div>
                          </td>
                          <td><span class="label label-warning">Error</span></td>
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
                        <tr>
                          <td class="ellipsis">0232312123</td>
                          <td class="ellipsis">
                            wan_ke_yun_cheng_20160208_item1_previewwan_ke_yun_cheng_20160208_item1_previewwan_ke_yun_cheng_20160208_item1_preview
                          </td>
                          <td>
                            <div class="progress" style="min-width: 200px; margin-bottom: 0px;">
                              <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                60%[20/2000912312512]
                              </div>
                            </div>
                          </td>
                          <td><span class="label label-danger">Unknown</span></td>
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
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
