@extends('core::layouts.master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h1 class="pull-left">Manual Entry</h1>
        </div>
    </div>

    <hr/>

    @include('flash::message')
    @include('core::components/error')

    
        {!! Form::open(['route' => 'timer.store']) !!}

            @include('timesheet::_fields')

        {!! Form::close() !!}
        
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('dist/page_timer_edit.js') }}"></script>
<script type="text/javascript">
    timesheetForm.init();
</script>
@endsection

