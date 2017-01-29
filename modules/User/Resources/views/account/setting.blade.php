@extends('core::layouts.master')

@section('content')
	
<div class="row">
    <div class="col-sm-12">
        <h1>Settings</h1>
    </div>
</div>

<hr/>

@include('flash::message')
@include('core::components/error')

<div class="row">


<div class="col-sm-9">
{!! Form::model($user, ['route' => ['account.setting'], 'method' => 'post']) !!}
    <div class="form-group col-sm-6">
        {!! Form::checkbox('daily_ot_report', 1, $setting['daily_ot_report']->value) !!}
        {!! Form::label('daily_ot_report', 'Send daily overtime report') !!}
    </div>

    <div class="form-group col-sm-6">
        {!! Form::checkbox('weekly_time_report', 1, $setting['weekly_time_report']->value) !!}
        {!! Form::label('weekly_time_report', 'Send weekly timesheet report') !!}
    </div>

    <div class="form-group col-sm-9">
            {!! Form::label('timezone', 'Timezone:') !!}
            {!! Form::select('timezone', $tzlist, $user->getTimezoneId(), ['class' => 'form-control', 'id' => 'tzlist']) !!}
        </div>

    <div class="form-group col-sm-12">
        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('account.view') !!}" class="btn btn-default">Cancel</a>
    </div>
{!! Form::close() !!}
</div>

</div>

@stop

@section('scripts')
<script src="{{ asset('lib/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
<script type="text/javascript">
    $("[name='daily_ot_report']").bootstrapSwitch();
    $("[name='weekly_time_report']").bootstrapSwitch();
    $('#tzlist').select2();
</script>
@endsection

@section('style')
<link href="{{ asset('lib/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('/lib/bootstrap-datetimepicker/dist/css/bootstrap-datetimepicker.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('/lib/jonthornton-jquery-timepicker/jquery.timepicker.css') }}" />
@endsection

