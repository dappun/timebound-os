@extends('core::layouts.master')

@section('content')
    <div class="clearfix"></div>

    @include('flash::message')

    <h1>Summary Report</h1>
    @include('timesheet::report/_form', ['url' => 'report.detailed'])

    <div class="clearfix"></div>
    <div class="reports-actions">
    	<ul class="list-inline pull-right">
    	<li><a href="{{ route('report.reset', ['d' => 'detailed']) }}">Reset</a></li>
    	</ul>

    	<div class="clearfix"></div>
    </div>

    <hr class="" />

    @if ($timesheets->all())
    @if ($total)
    <div class="timer-total pull-left">Total: <span>{{ gmhours($total) }}</span></div>
    @endif
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

    <canvas id="report_chart" style="width: 100%; height: 400px;"></canvas>

    <div class="clearfix"></div>

    <p>&nbsp;</p>

    @include('timesheet::_table')

    @else
    	<div class="well">No entries found. <a href="{{ route('report.reset', ['d' => 'detailed']) }}">Reset</a></div>
    @endif
        
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('dist/page_report.js') }}"></script>

<script type="text/javascript">
	var start = moment('<?php echo $request->input('start') ? $request->input('start') : date('Y-m-d', strtotime('monday this week')); ?>');
    var end = moment('<?php echo $request->input('end') ? $request->input('end') : date('Y-m-d'); ?>');
    var chartData = {!! json_encode($chartData) !!};

    report.initSearchForm(start, end, '#reportrange');
	report.initChart(chartData, 'report_chart');

</script>
@endsection