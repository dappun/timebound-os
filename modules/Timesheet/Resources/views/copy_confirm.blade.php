@extends('core::layouts.master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h1 class="pull-left">Copy Time</h1>
        </div>
    </div>

    <hr/>

    @include('flash::message')
    @include('core::components/error')

    
    {!! Form::model($timesheet, ['route' => ['timer.copy.post', $timesheet->id], 'method' => 'post']) !!}

    <p>You are about to restart timesheet #{{ $timesheet->id }}: {{ $timesheet->description }}. This operation will restart this entry.</p>
    {!! Form::submit('Do you want to continue?', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('timer.index') !!}" class="btn btn-default">Cancel</a>

    {!! Form::close() !!}

@endsection
