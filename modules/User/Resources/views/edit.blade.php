@extends('core::layouts.master')

@section('content')
	
<div class="row">
    <div class="col-sm-12">
        <h1>Edit Account</h1>
    </div>
</div>

<hr/>

@include('flash::message')
@include('core::components/error')

<div class="row">

    <div class="form-group col-sm-3">
        {!! Form::model($user, ['route' => ['avatar.upload', $user->id], 'method' => 'patch', 'files' => true, 'class' => 'dropzone']) !!}
        
        <img src="{{ url($user->profile_image) }}" class="profile-image">
        <div class="fallback">
            <input type="file" name="profile_image" id="profileImageFile" class="input-file" accept=".png, .jpeg, .jpg">
        </div>

        {!! Form::close() !!}            
    </div>

    <div class="form-group col-sm-9">
        {!! Form::model($user, ['route' => ['admin.users.update', $user->id], 'method' => 'patch']) !!}
        
        <div class="form-group col-sm-12">
        <label>Account</label>
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('name', 'Username:') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('email', 'Email:') !!}
            {!! Form::text('email', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('password', 'Password:') !!}
            {!! Form::password('password', ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('password_confirm', 'Confirm Password:') !!}
            {!! Form::password('password_confirm', ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-12">
        <hr />
        <label>Bio</label>
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('first_name', 'First Name:') !!}
            {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('last_name', 'Last Name:') !!}
            {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-12">
        <hr />
        <label>Permission</label>
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('status_id', 'Status:') !!}
            {!! Form::select('status_id', [0 => 'Inactive', 1 => 'Active'], null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('role', 'Access:') !!}
            {!! Form::select('role', $roles, null, ['class' => 'form-control']) !!}
        </div>

        

        <!-- Submit Field -->
        <div class="form-group col-sm-12">
            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            <a href="{!! route('admin.users.index') !!}" class="btn btn-default">Cancel</a>
        </div>

        {!! Form::close() !!}
    </div>
</div>

@stop

@section('scripts')
<script src="{{ asset('lib/dropzone.js') }}"></script>
<script type="text/javascript">
    Dropzone.options.profileImageFile = {
        paramName: "profile_image", // The name that will be used to transfer the file
        maxFilesize: 2, // MB
        uploadMultiple: false,
        acceptedFiles: 'image/*',
        maxFiles: 1,
        thumbnailWidth: '200',
        init: function() {
            this.on("success", function(file, responseText) {
                document.querySelector(".profile-image").style.opacity = "0";
            });
        }
    };
</script>
@endsection