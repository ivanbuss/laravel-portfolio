<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Page;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;

class PagesController extends AdminController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List pages action
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getListPages(Request $request) {
        return view('admin.pages.list', ['page'=>'pages', 'count'=>0]);
    }

    public function getListPagesTableData(Request $request) {
        $columns = [
            0 => ['name'=>'users.email'],
            1 => ['name'=>'pages.title'],
            2 => ['name'=>'pages.url'],
            3 => ['name'=>'pages.created_at'],
            4 => ['name'=>'pages.updated_at'],
            4 => [],
        ];
        $count = 0;
        $orders = $request->get('order') ? $request->get('order') : [];

        $query = Page::select('pages.*', 'users.email AS email')
            ->leftjoin('users', 'users.id', '=', 'pages.user_id');

        $recordsTotal = $query->count();

        $search = $request->get('search') ? $request->get('search') : [];
        if ($search['value']) {
            $query->where(function($subquery) use ($search) {
                $subquery->where('pages.title', 'LIKE', '%'.$search['value'].'%')
                    ->orWhere('pages.url', 'LIKE', '%'.$search['value'].'%');
            });
        }
        $recordsFiltered = $query->count();

        $orders = $request->get('order') ? $request->get('order') : [];
        $this->dataTableSorting($query, $columns, $orders);

        $length = $request->get('length') ? $request->get('length') : 10;
        $start = $request->get('start') ? $request->get('start') : 0;
        $draw = $request->get('draw') ? $request->get('draw') : 1;

        $collective = $query->offset($start)->limit($length)->get();
        $items = [];
        foreach($collective as $item) {
            $items[] = [
                '<a href="'.action('ProfileController@show', $item->user_id).'">'.$item->email.'</a>',
                $item->title,
                $item->url,
                $item->status ? 'Active' : 'Inactive',
                $item->created_at->format('m/d/Y'),
                $item->updated_at->format('m/d/Y'),
                '<a href="'.action('Admin\PagesController@getEditPage', $item->id).'">Edit</a>'
            ];
        }

        return json_encode(['draw'=>$draw, 'recordsTotal'=>$recordsTotal, 'recordsFiltered'=>$recordsFiltered, 'data'=>$items]);
    }

    /**
     * Admin create static page route
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreatePage(Request $request) {
        return $this->showCreatePageForm();
    }


    public function showCreatePageForm(Page $page = null) {
        if ($page) {
            return view('admin.pages.edit', ['page'=>'pages', 'page_model'=>$page]);
        } else {
            return view('admin.pages.create', ['page'=>'pages']);
        }
    }


    public function postCreate(Request $request) {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $this->createPage($request, $request->user());
        return redirect()->action('Admin\PagesController@getListPages')->with('message', 'New page has been created');
    }

    public function getEditPage(Page $page, Request $request) {
        return $this->showCreatePageForm($page);
    }

    public function postUpdate(Page $page, Request $request) {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $this->updatePage($page, $request);
        return redirect()->action('Admin\PagesController@getListPages')->with('message', 'Page has been updated');
    }

    public function postDelete(Page $page, Request $request) {
        $page->delete();
        return redirect()->action('Admin\PagesController@getListPages')->with('message', 'Page has been deleted');
    }

    /**
     *
     * Create page handler
     * @param Request $request
     * @return static
     */
    protected function createPage(Request $request, User $user) {
        $page = Page::create([
            'user_id' => $user->id,
            'title' => $request->get('title'),
            'url' => $request->get('url'),
            'body' => $request->get('body'),
            'status' => $request->get('status'),
        ]);
        return $page;
    }

    protected function updatePage(Page $page, Request $request) {
        $page->title = $request->get('title');
        $page->url = $request->get('url');
        $page->body = $request->get('body');
        $page->status = $request->get('status');
        $page->save();
    }

    protected function validator(array $data) {
        return Validator::make($data, [
            'title' => 'required|max:255',
            'url' => 'required|max:255',
            'body' => 'required',
            'status' => 'boolean',
        ]);
    }

}
