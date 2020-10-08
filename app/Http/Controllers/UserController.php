<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('user.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'edad' => 'min:0|integer|max:200',
            'dni' => 'min:0|integer|max:99999999',
        ]);
        if (User::create($validateData)) {        
            session()->flash('status', 'El usuario ha sido dado de alta');        
            return redirect(route('user.index'));    
        }
        session()->flash('status', 'No se pudo dar de alta el usuario. Vuelva a intentarlo');
        return back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('user.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'edad' => 'min:0|integer|max:200',
            'dni' => 'min:0|integer|max:99999999',
        ]);
        $user->name = $request->name;
        $user->dni = $request->dni;
        $user->edad = $request->edad;
        $user->email = $request->email;
        $user->save();

        return redirect(route('user.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->delete()) {
            session()->flash('status', 'El dispositivo ha sido borrado');
            return redirect(route('user.index'));
        }
        session()->flash('status', 'No se pudo borrar el dispositivo. Vuelva a intentarlo');
        return back()->withInput();

    }
}
