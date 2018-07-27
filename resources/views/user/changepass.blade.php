@extends('layouts.app_admin')
@section('content')
@section('title', 'Change Passowrd')
<section class="content-header">
  @include('partial.breadcome')
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
        	@include('partial.message')
        	{!! Form::open(['route' => ['postCredentials'] ]) !!}
			  <div class="col-md-6">             
			    <label for="current-password" class="col-sm-4">Current Password</label>
			    <div class="col-sm-8">
			      <div class="form-group">
			        <input type="hidden" name="_token" value="{{ csrf_token() }}"> 
			        <input type="password" class="form-control" id="current-password" name="current-password" placeholder="Password">
			      </div>
			    </div>
			    <label for="password" class="col-sm-4">New Password</label>
			    <div class="col-sm-8">
			      <div class="form-group">
			        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
			      </div>
			    </div>
			    <label for="password_confirmation" class="col-sm-4">Re-enter Password</label>
			    <div class="col-sm-8">
			      <div class="form-group">
			        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Re-enter Password">
			      </div>
			    </div>
			  </div>
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-6">
			      <button type="submit" class="btn btn-danger">Submit</button>
			    </div>
			  </div>
			{!! Form::close() !!}
        </div>
    </div>
</section>
@endsection