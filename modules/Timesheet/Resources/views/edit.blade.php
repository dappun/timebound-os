@extends('core::layouts.master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h1 class="pull-left">Edit Time</h1>
        </div>
    </div>

    <hr/>

    @include('flash::message')
    @include('core::components/error')

    
    {!! Form::model($timesheet, ['route' => ['timer.update', $timesheet->id], 'method' => 'patch']) !!}

    @include('timesheet::_fields')

    {!! Form::close() !!}

    <div class="row">
        <div class="col-sm-6">
            <br/>
            <br/>
            <br/>
            {!! Form::open(['route' => ['timer.destroy', $timesheet->id], 'method' => 'delete']) !!}
            
            {!! Form::button('Delete this entry', ['type' => 'submit', 'class' => 'btn btn-link', 'onclick' => "return confirm('Are you sure?')"]) !!}

            {!! Form::close() !!}
        </div>
    </div>

@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('dist/page_timer_edit.js') }}"></script>
<script type="text/javascript">
    timesheetForm.init();
</script>
@endsection

