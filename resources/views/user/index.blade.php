@extends('layouts.app')

@section('content')

<h1 class="text-primary">Control de Usuarios</h1>

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

        {!! Form::open(['route' => ['pasteles.destroy', $pastel->id], 'method' => 'DELETE']) !!}

            <td class="text-center">
                <button type="submit" class="btn btn-danger btn-xs">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                </button>
                <a href="{{ url('/pasteles/'.$pastel->id.'/edit') }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                </a>
            </td>

        {!! Form::close() !!}

        </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
        <th class="text-center">ID</th>
        <th class="text-center">Nombre</th>
        <th class="text-center">Sabor</th>
        <th class="text-center">Fecha</th>
        <th class="text-center">Acciones</th>
    </tr>
  </tfoot>
</table>

@endsection