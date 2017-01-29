@extends('core::layouts.master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h1 class="pull-left">Create New Project</h1>
        </div>
    </div>

    <hr/>

    @include('flash::message')
    @include('core::components/error')

    <div class="row">
        {!! Form::open(['route' => 'projects.store']) !!}

            @include('project::fields')

        {!! Form::close() !!}
    </div>
@endsection
