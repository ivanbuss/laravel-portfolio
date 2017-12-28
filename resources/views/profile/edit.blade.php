@extends('layouts.app')

@section('content')
    @include('header-white')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div id="profile-edit-panel" class="panel panel-default">
                    <div class="panel-heading">Edit profile</div>
                    <div class="panel-body">
                        {!! Form::open([
                                'action'=>['ProfileController@postEdit', $user],
                                'class'=>'form-horizontal',
                                'role'=>'form',
                                'files' => true,
                         ]) !!}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            {!! Form::label('name', 'PROFILE NAME', ['class'=>'col-md-4 control-label']) !!}

                            <div class="col-md-6">
                                {!! Form::text(
                                    'name',
                                    $profile->name,
                                    ['class'=>'form-control'])
                                !!}
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('avatar_img') ? ' has-error' : '' }}">
                            {!! Form::label('avatar_img', 'AVATAR', ['class'=>'col-md-4 control-label']) !!}

                            <div class="col-md-6">
                                {!! Form::file('avatar_img') !!}
                                <p class="help-block">5mb size limit</p>
                                @if ($errors->has('avatar_img'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('avatar_img') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('background_img') ? ' has-error' : '' }}">
                            {!! Form::label('background_img', 'BACKGROUND IMAGE', ['class'=>'col-md-4 control-label']) !!}

                            <div class="col-md-6">
                                {!! Form::file('background_img') !!}
                                <p class="help-block">5mb size limit</p>
                                @if ($errors->has('background_img'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('background_img') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('bio') ? ' has-error' : '' }}">
                            {!! Form::label('bio', 'BIO', ['class'=>'col-md-4 control-label']) !!}

                            <div class="col-md-6">
                                {!! Form::textarea(
                                    'bio',
                                    $profile->bio,
                                    ['class'=>'form-control'])
                                 !!}
                                <p class="help-block">Tell a little about yourself</p>
                                @if ($errors->has('bio'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('bio') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('age') ? ' has-error' : '' }}">
                            {!! Form::label('age', 'AGE', ['class'=>'col-md-4 control-label']) !!}

                            <div class="col-md-6">
                                {!! Form::selectRange('birthday_day', 1, 31, $profile->birthday ? $profile->birthday->format('d') : null, ['class'=>'form-control', 'placeholder'=>'-day-']) !!}
                                {!! Form::selectMonth('birthday_month', $profile->birthday? $profile->birthday->format('m') : null, ['class'=>'form-control', 'placeholder'=>'-month-']) !!}
                                {!! Form::selectYear('birthday_year', (Carbon::now()->format('Y') - 100), Carbon::now()->format('Y'), $profile->birthday ? $profile->birthday->format('Y') : null, ['class'=>'form-control', 'placeholder'=>'-year-']) !!}
                                @if ($errors->has('age'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('age') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                            {!! Form::label('gender', 'GENDER', ['class'=>'col-md-4 control-label']) !!}

                            <div class="col-md-6">
                                {!! Form::select(
                                    'gender',
                                    ['m'=>'Male', 'f'=>'Female'],
                                    $profile->gender,
                                    ['class'=>'form-control', 'placeholder'=>'--Select--'])
                                !!}
                                @if ($errors->has('gender'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                            {!! Form::label('country', 'COUNTRY', ['class'=>'col-md-4 control-label']) !!}

                            <div class="col-md-6">
                                {!! Form::select(
                                    'country',
                                    $countries,
                                    $profile->country,
                                    ['class'=>'form-control', 'placeholder'=>'--Select Country--'])
                                !!}
                                @if ($errors->has('country'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('country') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        @role('admin')
                        <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
                            {!! Form::label('roles', 'Role', ['class'=>'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::select(
                                    'roles',
                                    $roles,
                                    $defaultRoles,
                                    ['class'=>'form-control', 'multiple', 'name' => 'roles[]'])
                                !!}
                                @if ($errors->has('roles'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('roles') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endrole

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>Save
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
