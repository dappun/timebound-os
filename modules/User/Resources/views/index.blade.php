@extends('core::layouts.master')

@section('content')
<div id="table-team">
	<div class="row">
	    <div class="col-sm-6">
	        <h1 class="pull-left">Team</h1>
	        
	    </div>

	    <div class="col-sm-5">
	    	<input class="form-control search" placeholder="Search" />
	    </div>

	    <div class="col-sm-1">
	    	<a class="btn btn-primary pull-right" href="{!! route('admin.users.create') !!}">Add New</a>
	    </div>
	</div>

	@include('flash::message')

	<br/><br/>
	<hr />
	<br/><br/>

	<div class="row">
		<div class="col-sm-12">

	        @include('user::table')

		</div>
	</div>
</div>

@stop

