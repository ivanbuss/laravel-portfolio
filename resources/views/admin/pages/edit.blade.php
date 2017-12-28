@extends('admin.layouts.backend')

@section('content')
    <h2 class="page-title">Edit Page</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    {!! Form::open(['action'=>['Admin\PagesController@postUpdate', $page_model->id], 'class'=>'form-horizontal']) !!}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Page Title</label>
                            <div class="col-sm-10">
                                {!! Form::text('title', $page_model->title, ['class'=>'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Page URL</label>
                            <div class="col-sm-10">
                                {!! Form::text('url', $page_model->url, ['class'=>'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Page Body</label>
                            <div class="col-sm-10">
                                {!! Form::textarea('body', $page_model->body, ['class'=>'form-control editor']) !!}
                            </div>
                        </div>
                    <div class="hr-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Status
                            <br>
                        </label>
                        <div class="col-sm-5">
                            <div class="radio">
                                {!! Form::radio('status', 1, ($page_model->status ? TRUE : FALSE), ['id'=>'status-active']) !!}
                                <label for="status-active">
                                    Active
                                </label>
                            </div>
                            <div class="radio">
                                {!! Form::radio('status', 0, ($page_model->status ? FALSE : TRUE), ['id'=>'status-inactive']) !!}
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
                                <a data-toggle="modal" data-target="#delete-page" class="btn btn-default" href="#">Delete</a>
                                {!! Form::submit('Update page', ['class'=>'btn btn-primary']) !!}
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <div id="delete-page" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete Page</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure to delete "{{ $page_model->title }}" page</p>
                </div>
                <div class="modal-footer">
                    {!! Form::open(['action'=>['Admin\PagesController@postDelete', $page_model->id]]) !!}
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        {!! Form::submit('Delete', ['class'=>'btn btn-primary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection