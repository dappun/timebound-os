@extends('core::layouts.master')

@section('content')
	
<div class="row">
    <div class="col-sm-12">
        <h1>My Account</h1>
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
        <div class="row">

        {!! Form::model($user, ['route' => ['account.update'], 'method' => 'post']) !!}

        <div class="form-group col-sm-9">
            {!! Form::label('name', 'Name:') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-9">
            {!! Form::label('email', 'Email:') !!}
            {!! Form::text('email', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-9">
            {!! Form::label('full_name', 'Full Name:') !!}
            {!! Form::text('full_name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-12">
            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            <a href="{!! route('account.view') !!}" class="btn btn-default">Cancel</a>
        </div>

        {!! Form::close() !!}

        </div>

    </div>

</div>

<div class="row">

    <div class="form-group col-sm-3">
    </div>

    <div class="col-sm-9">
        <div class="row">

        <div class="form-group col-sm-12">
            <hr />  
            <h3>Change Password</h3>
        </div>

        {!! Form::model($user, ['route' => ['account.password'], 'method' => 'post']) !!}
            <div class="form-group col-sm-6">
                {!! Form::label('password', 'Password:') !!}
                {!! Form::password('password', ['class' => 'form-control', 'autocomplete' => 'off']) !!}
            </div>

            <div class="form-group col-sm-6">
                {!! Form::label('password_confirmation', 'Confirm Password:') !!}
                {!! Form::password('password_confirmation', ['class' => 'form-control', 'autocomplete' => 'off']) !!}
            </div>

            <div class="form-group col-sm-12">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{!! route('account.view') !!}" class="btn btn-default">Cancel</a>
            </div>
        {!! Form::close() !!}

        </div>

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