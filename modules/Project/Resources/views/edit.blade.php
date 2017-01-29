@extends('core::layouts.master')

@section('content')
        <div class="row">
            <div class="col-sm-12">
                <h1 class="pull-left">Edit Project</h1>
            </div>
        </div>

        <hr/>

        @include('flash::message')
        @include('core::components/error')

        <div class="row">
            {!! Form::model($project, ['route' => ['projects.update', $project->id], 'method' => 'patch']) !!}

            @include('project::fields')

            {!! Form::close() !!}
        </div>
@endsection
