@extends('core::layouts.master')

@section('content')
<div id="table-project">
	<div class="row">
	    <div class="col-sm-6">
	        <h1 class="pull-left">Projects</h1>
	    </div>

	    <div class="col-sm-5">
	    	<input class="form-control search" placeholder="Search" />
	    </div>

	    <div class="col-sm-1">
	    	<a class="btn btn-primary pull-right" href="{!! route('projects.create') !!}">Add New</a>
	    </div>
	</div>

    <div class="clearfix"></div>

    <hr/>
    @include('flash::message')

    <div class="clearfix"></div>

    <div class="row">
    	<div class="col-sm-12">
    		@include('project::table')
    	</div>
    </div>
    
</div>
@endsection
