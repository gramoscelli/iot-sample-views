<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    function test_prueba_de_listar_clientes_con_lista_vacia()
    {
	DB::Table('clients')->truncate();
        $this->get('/client')
             ->assertStatus(200)
             ->assertSee('No se encontraron clientes');
   }

    function test_prueba_de_listar_un_usuario()
    {
	Client::create(['name'=>'Pedro Gomez']);

        $this->get('/client')
             ->assertStatus(200)
             ->assertSee('Pedro Gomez');
   }

}
