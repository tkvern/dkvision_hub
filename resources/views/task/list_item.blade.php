<tr>
    @if($task->isEmergency())
    <td class="ellipsis"> 
        {{ $task->title }} <span class="badge" style="background-color: #d9534f">急</span> 
    </td>
    @else
    <td class="ellipsis"> {{ $task->title }} </td>
    @endif
    <td class="ellipsis"> 
        {{ App\Task::$RENDER_TYPE[$task->payload['task_type']] }}
        @if ($task->task_type === "TOP_BOTTOM")
            @if(is_true($task->payload['enable_top']))
                <span class="badge">顶</span>
            @endif
            @if(is_true($task->payload['enable_bottom']))
                <span class="badge">底</span>
            @endif
        @endif
    </td>
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
    <td>{!! $task->status_label() !!} </td>
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
                    <li>
                        <a class="retry" 
                            remote
                            data-method="post"
                            data-confirm="确认要重试?"
                            data-target="/tasks/{{ $task->id }}/retry">
                            重试
                        </a>
                    </li>
                    @endif
                    @if($task->canTerminate())
                    <li>
                        <a class="terminate" 
                            remote
                            data-method="post"
                            data-confirm="确认要终止?"
                            data-target="/tasks/{{ $task->id }}/terminate">
                            终止
                        </a>
                    </li>
                    @endif
                    @if($task->canDelete())
                    <li>
                        <a class="delete" 
                            remote
                            data-method="delete"
                            data-confirm="确认要删除?"
                            data-target="{{ url('/tasks', [$task->id]) }}">
                            删除
                        </a>
                    </li>
                    @endif
                    @if($task->canCreateTopAndBottom())
                    <li role="presentation" class="divider"></li>
                    <li>
                        <a remote
                            data-method="POST" 
                            data-confirm="确认生成顶部?"
                            data-values='{"enable_top": 1}'
                            data-target="/tasks/{{ $task->id }}/topbottom">
                            生成顶
                        </a>
                    </li>
                    <li>
                        <a remote
                            data-method="POST" 
                            data-confirm="确认生成底部?"
                            data-values='{"enable_bottom": 1}'
                            data-target="/tasks/{{ $task->id }}/topbottom">
                            生成底
                        </a>
                    </li>
                    <li>
                        <a remote 
                            data-method="POST" 
                            data-confirm="确认生成顶部和底部?"
                            data-values='{"enable_top": 1, "enable_bottom": 1}'
                            data-target="/tasks/{{ $task->id }}/topbottom">
                            生成顶和底
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </td>
</tr>