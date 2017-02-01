@extends('core::layouts.master')

@section('content')
    <div class="clearfix"></div>

    @include('flash::message')

    <h1>Weekly Report</h1>
    @include('timesheet::report/_form', ['url' => 'report.teamweekly'])

    <div class="clearfix"></div>
    <div class="reports-actions">
    	<ul class="list-inline pull-right">
    	<li><a href="{{ route('report.detailed') }}">Reset</a></li>
    	</ul>

    	<div class="clearfix"></div>
    </div>

    <hr class="" />

    @if ($rows)
    <div class="actions">
    	<ul class="list-inline pull-right">
    	   <li>
                <a href="{{ route('report.download', ['list'] + $urlQuery) }}">
                    <i class="fa fa-download" aria-hidden="true"></i> CSV
                </a>   
            </li>
    	</ul>

    	<div class="clearfix"></div>
    </div>

    <div class="clearfix"></div>

    <p>&nbsp;</p>

    <table class="table">
        <thead>
        <tr>
            @foreach ($header as $name)
                <td>{!! $name !!}</td>
            @endforeach
        </tr>
        </thead>

        <tbody>
            @foreach ($rows as $row)
            <tr>
                @foreach ($row as $key => $content)
                @if ($key > 0)
                    @if ($content != '00:00')
                    <td>{{ $content }}</td>
                    @else
                    <td><label>{{ $content }}</label></td>
                    @endif
                @else
                <td>{{ $content }}</td>
                @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    @else
    	<div class="well">No entries found. <a href="{{ route('report.detailed') }}">Reset?</a></div>
    @endif
        
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('dist/page_report.js') }}"></script>
<script type="text/javascript">
	var start = moment('<?php echo $request->input('start') ? $request->input('start') : date('Y-m-d', strtotime('monday this week')); ?>');
    var end = moment('<?php echo $request->input('end') ? $request->input('end') : date('Y-m-d'); ?>');
    
    report.initSearchForm(start, end, '#reportrange');
</script>
@endsection