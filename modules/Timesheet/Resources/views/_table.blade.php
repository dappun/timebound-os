<table class="table table-responsive" id="timesheets-table">
    <thead>
        <tr>
            <th><label>Description</label></th>
            <th><label>Project</label></th>
            @if (\Auth::user()->hasRole('admin') && isset($users) && $users)
            <th><label>User</label></th>
            @endif
            <th><label>Duration</label></th>
            <th><label>Time Entry</label></th>
        </tr>
    </thead>
    <tbody>
    <?php $lastDate = '' ?>
    @foreach($timesheets as $timesheet)
        <?php
        $colspan = 4;
        if (\Auth::user()->hasRole('admin') && isset($users) && $users) {
            $colspan = 5;
        }
        
        $d = date('Y-m-d', strtotime($timesheet->startConverted()));
        if ($lastDate != $d) {
            echo '<tr><td colspan='.$colspan.'><h1>'. timeElapsedString($d) .'</h1></td></tr>';
        }

        $lastDate = $d;

        $class = '';
        $warning = '';
        if ($timesheet->getOriginal('duration') > Config::get('timesheet.overtime.notice')) {
            $warning = 'alert-warning';

            if ($timesheet->getOriginal('duration') > Config::get('timesheet.overtime.warning')) {
                $warning = 'alert-danger';
            }
        }
        ?>
        <tr class="{{ $warning }}">
            <td class="table-desc">
                {!! $timesheet->description !!}
            </td>
            <td class="table-project">
                {!! $timesheet->project_title !!}
                @if ($timesheet->ticket)
                <span class="label-ticket">{!! $timesheet->ticket !!}</span>
                @endif

                @if ($timesheet->user_id == \Auth::id() || \Auth::user()->hasRole('admin'))
                <div class="action">
                    {!! Form::open(['route' => ['timer.destroy', $timesheet->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <ul class="list-inline">
                            <li>
                                <a href="{!! route('timer.edit', [$timesheet->id]) !!}" class='btn btn-default btn-primary btn-xs'><i class="glyphicon glyphicon-pencil"></i></a>
                            </li>
                            <li>{!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}</li>
                        </ul>
                        
                    </div>
                    {!! Form::close() !!}
                </div>
                @endif
            </td>
            @if (\Entrust::hasRole('admin') && isset($users) && $users)
            <td>{{ $timesheet->user_name }}</td>
            @endif
            <td class="table-time">
                @if ($timesheet->duration == '00:00:00')
                <span class="gray">{{ $timesheet->duration }}</span>
                @else
                    @if ($warning == '')
                    <span class="bold">{{ $timesheet->duration }}</span>
                    @else
                    <span class="bold"><b>{{ $timesheet->duration }}</b></span>
                    @endif
                @endif
            </td>
            <td>
                <span class="nowrap">{!! date('h:i a', strtotime($timesheet->startConverted())) !!} - {!! date('h:i a', strtotime($timesheet->endConverted())) !!}</span>
            </td>
            
        </tr>
    @endforeach
    </tbody>
</table>

@if ($timesheets->count() > 1)

    @if (isset($urlQuery) && $urlQuery)
        {{ $timesheets->appends($urlQuery)->links() }}
    @else
        {{ $timesheets->links() }}
    @endif

@endif