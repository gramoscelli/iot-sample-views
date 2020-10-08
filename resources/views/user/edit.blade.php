@extends('layouts.app')

@section('content')

  <form method='post' action="/user/{{ $user->id }}">
  @csrf
  @method('put')
  Nombre :
  <input type="text" name='name' value="{{ $user->name }}"></input><br>
  Email:
  <input type="text" name='email' value="{{ $user->email }}"></input><br>
  Edad:
  <input type="text" name='edad' value="{{ $user->edad }}"></input><br>
  DNI:
  <input type="text" name='dni' value="{{ $user->dni }}"></input><br>
  PAssowrd:
  <input type="password" name='password' value="{{ $user->password }}"></input><br>
  <input type="submit" value="Aceptar"> 
</form>

@endsection('content')
