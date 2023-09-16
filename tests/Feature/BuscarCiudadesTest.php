<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Symfony\Component\HttpFoundation\Response;

class BuscarCiudadesTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {



        $departamentoId = 76;
        $requestData = [
            'id' => $departamentoId,
        ];

        // Realizar la solicitud GET al controlador con el ID del departamento
        $response = $this->json('POST', '/getCiudades', $requestData);


        $response->assertStatus(Response::HTTP_OK);

        $data = $response->json();
    }
}
