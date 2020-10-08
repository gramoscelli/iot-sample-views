@extends('layouts.app')

@section('content')

<div class="container">
        <div class="panel panel-default">
              <div class="panel-body">
              <br>
              <br>

<div>
  <form method='post' action="/user">
  @csrf
  <table>
    <tr>
      <td>Nombre :</td>
      <td><input type="text" name='name'></input></td>
    </tr>
    <tr>
      <td><label>Email:</label></td>
      <td><input type="text" name='email'></input></td>
    </tr>
    <tr>
      <td>Edad:</td>
      <td><input type="text" name='edad'></input><br></td>
  </tr>
    <tr>
    <td>DNI:</td>
    <td><input type="text" name='dni'></input><br></td>
  </tr>
    <tr>
    <td>Passowrd:</td>
    <td><input type="password" name='password'></input><br></td>
  </tr>
    <tr>
    <td colspan="2"><input type="submit" value="Aceptar"></td>
  </tr>
  </form>
</div>
</div>
        </div>
        </div>
        </div>
</div>

@endsection('content')

