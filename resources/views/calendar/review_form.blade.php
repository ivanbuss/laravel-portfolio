@extends('layouts.app')

@section('content')
    @include('header-white')
    <div class="container-fluid no-padding" id="addNewDeck">
        {!! Form::open(['action'=>['LaunchCalendarController@postReviewAdd', $deck->id], 'id'=>'add-deck-form', 'class'=>'form-horizontal', 'role'=>'form', 'files' => true]) !!}
            <div class="main">
                <div class="container">
                    <div class="col-md-12{{ $errors->has('body') ? ' has-error' : '' }}">
                        {!! Form::label('image', 'Image', ['class'=>'control-label']) !!}
                        {!! Form::file('image') !!}
                        @if ($errors->has('body'))
                            <span class="help-block">
                                <strong>{{ $errors->first('image') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-12{{ $errors->has('body') ? ' has-error' : '' }}">
                        {!! Form::label('body', 'Review', ['class'=>'control-label']) !!}
                        {!! Form::textarea('body', old('body'), ['class'=>'form-control', 'placeholder'=>'Write review here']) !!}
                        @if ($errors->has('body'))
                            <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <button id="gallery-create-item-button" type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-user"></i>Create
                        </button>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection
