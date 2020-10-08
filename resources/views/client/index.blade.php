@extends('layouts.app')



@section('content')


<div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
              <div style="float: left; padding-top: 15px; padding-left: 5px;"><h3>Usuarios del Sistema</h3></div>
                <div style="float: right; padding-top: 10px;  padding-bottom: 10px; padding-right: 5px;">
                    <a href="/client/create" class="btn btn-success">
                        <i class="fa fa-plus"></i> Agregar Usuario
                    </a>
                </div>
              </div>
              <div class="panel-body">
              <br>
              <br>
              @if (count($clients))
              <table class="table table-bordered" id="MyTable">
                <thead>
                  <tr>
                      <th class="text-center">Nombre</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($clients as $client)
                      <tr>
                          <td class="text-center">{{ $client->name }}</td>
                      </tr>
                  @endforeach
                </tbody>
              </table>
              @else
                    <p class="alert alert-info">
                        No se encontraron clientes
                    </p>
              @endif
          </div>
        </div>
        </div>
        </div>
</div>

@endsection