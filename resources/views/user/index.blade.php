@extends('layouts.app')



@section('content')


<div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
              <div style="float: left; padding-top: 15px; padding-left: 5px;"><h3>Usuarios del Sistema</h3></div>
                <div style="float: right; padding-top: 10px;  padding-bottom: 10px; padding-right: 5px;">
                    <a href="/user/create" class="btn btn-success">
                        <i class="fa fa-plus"></i> Agregar Usuario
                    </a>
                </div>
              </div>
              <div class="panel-body">
              <br>
              <br>
              @if (count($users))
              <table class="table table-bordered" id="MyTable">
                <thead>
                  <tr>
                      <th class="text-center">Nombre</th>
                      <th class="text-center">Username</th>
                      <th class="text-center">Email</th>
                      <th class="text-center">DNI</th>
                      <th class="text-center">Edad</th>
                      <th class="text-center">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($users as $user)
                      <tr>
                          <td class="text-center">{{ $user->name }}</td>
                          <td class="text-center">{{ $user->username }}</td>
                          <td class="text-center">{{ $user->email }}</td>
                          <td class="text-center">{{ $user->dni }}</td>
                          <td class="text-center">{{ $user->edad }}</td>
                      </tr>
                  @endforeach
                </tbody>
              </table>
              @else
                    <p class="alert alert-info">
                        No se encontraron usuarios
                    </p>
              @endif
          </div>
        </div>
        </div>
        </div>
</div>

@endsection