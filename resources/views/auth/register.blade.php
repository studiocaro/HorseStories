@extends('layout.app')

@section('content')
    @include('layout.partials.errors')

    {{ Form::open(['class' => 'form-horizontal']) }}
    <!-- Name Form input -->
    <div class="form-group">
        {{ Form::label('username', trans('forms.labels.username'), ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-6">
            {{ Form::text('username', null, ['class' => 'form-control']) }}
        </div>
    </div>

    <!-- Email Form input -->
    <div class="form-group">
        {{ Form::label('email', trans('forms.labels.email'), ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-6">
            {{ Form::text('email', null, ['class' => 'form-control']) }}
        </div>
    </div>

    <!-- Password Form input -->
    <div class="form-group">
        {{ Form::label('password', trans('forms.labels.password'), ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-6">
            {{ Form::password('password', ['class' => 'form-control']) }}
        </div>
    </div>

    <!-- Password_confirmation Form input -->
    <div class="form-group">
        {{ Form::label('password_confirmation', trans('forms.labels.password_confirmation'), ['class' => 'col-md-4
        control-label']) }}
        <div class="col-md-6">
            {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
        </div>
    </div>

    <!-- Submit button -->
    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            {{ Form::submit(trans('forms.buttons.signup'), ['class' => 'btn btn-default']) }}
        </div>
    </div>

    {{ Form::close() }}

@endsection
