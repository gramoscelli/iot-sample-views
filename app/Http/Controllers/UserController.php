<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

/*
  RESTfull /user Routes:

+-----------+------------------+--------------+---------------------------------------------+------------+
| Method    | URI              | Name         | Action                                      | Middleware |
+-----------+------------------+--------------+---------------------------------------------+------------+
| GET|HEAD  | user             | user.index   | App\Http\Controllers\UserController@index   | web        |
| POST      | user             | user.store   | App\Http\Controllers\UserController@store   | web        |
| GET|HEAD  | user/create      | user.create  | App\Http\Controllers\UserController@create  | web        |
| GET|HEAD  | user/{user}      | user.show    | App\Http\Controllers\UserController@show    | web        |
| PUT|PATCH | user/{user}      | user.update  | App\Http\Controllers\UserController@update  | web        |
| DELETE    | user/{user}      | user.destroy | App\Http\Controllers\UserController@destroy | web        |
| GET|HEAD  | user/{user}/edit | user.edit    | App\Http\Controllers\UserController@edit    | web        |
+-----------+------------------+--------------+---------------------------------------------+------------+


*/

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // load all the users table in $users (collection)
        $users = User::all();
        // return the user.index view
        return view('user.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
