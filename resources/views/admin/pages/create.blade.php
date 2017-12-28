@extends('admin.layouts.backend')

@section('content')
    <h2 class="page-title">Create a New Page</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    {!! Form::open(['action'=>'Admin\PagesController@postCreate', 'class'=>'form-horizontal']) !!}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Page Title</label>
                            <div class="col-sm-10">
                                {!! Form::text('title', null, ['class'=>'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Page URL</label>
                            <div class="col-sm-10">
                                {!! Form::text('url', null, ['class'=>'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Page Body</label>
                            <div class="col-sm-10">
                                {!! Form::textarea('body', null, ['class'=>'form-control editor']) !!}
                            </div>
                        </div>
                    <div class="hr-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Status
                            <br>
                        </label>
                        <div class="col-sm-5">
                            <div class="radio">
                                {!! Form::radio('status', 1, null, ['id'=>'status-active']) !!}
                                <label for="status-active">
                                    Active
                                </label>
                            </div>
                            <div class="radio">
                                {!! Form::radio('status', 0, true, ['id'=>'status-inactive']) !!}
                                <label for="status-inactive">
                                    Inactive
                                </label>
                            </div>
                        </div>
                    </div>
                        <div class="hr-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-2">
                                <a class="btn btn-default" href="{{ action('Admin\PagesController@getListPages') }}">Cancel</a>
                                {!! Form::submit('Create page', ['class'=>'btn btn-primary']) !!}
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection