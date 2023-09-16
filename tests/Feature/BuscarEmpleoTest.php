<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmpleosController;
use App\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class BuscarEmpleoTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testBuscarTest()
    {
     // Preparar el JSON de solicitud
     $requestData = [
        'campo' => 'ingeniero',
        'depar' => 'all',
        'tipo' => '1',
        'filtros' => [
            [
                'name' => 'Tipo de contrato',
                'field' => 'contrato',
                'type' => 'check',
                'campo' => '',                
            ],
            [
                'name' => 'Experiencia',
                'field' => 'experiencia',
                'type' => 'check',
                'campo' => '',    
                'campos'=>[
                    [
                        'campo' => 'exp',
                        'id' => 1,
                        'valor' => 'Primer empleo',
                        'bd' => [0, 0],
                    ],
                    [
                        'campo' => 'exp',
                        'id' => 2,
                        'valor' => 'Menos de 6 meses',
                        'bd' => [0, 6],
                    ],
                    [
                        'campo' => 'exp',
                        'id' => 3,
                        'valor' => 'Menos de 1 año',
                        'bd' => [6, 12],
                    ],
                    [
                        'campo' => 'exp',
                        'id' => 4,
                        'valor' => 'Entre 1 y 2 años',
                        'bd' => [12, 24],
                    ],
                    [
                        'campo' => 'exp',
                        'id' => 5,
                        'valor' => 'Entre 2 y 5 años',
                        'bd' => [24, 60],
                    ],
                    [
                        'campo' => 'exp',
                        'id' => 6,
                        'valor' => 'Entre 5 y 10 años',
                        'bd' => [60, 120],
                    ],
                    [
                        'campo' => 'exp',
                        'id' => 7,
                        'valor' => 'Más de 10 años',
                        'bd' => [120, 10000],
                    ],
                ]            
            ],
            [
                'name' => 'Sueldo',
                'field' => 'sueldo',
                'type' => 'range',
                'campo' => [0, 20], 
                'campos' => [
                    [
                        'campo' => 'sueldo',
                        'id' => 0,
                        'valor' => '$ 650.000',
                        'bd' => 650000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 1,
                        'valor' => '$ 1.000.000',
                        'bd' => 1000000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 2,
                        'valor' => '$ 1.500.000',
                        'bd' => 1500000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 3,
                        'valor' => '$ 2.000.000',
                        'bd' => 2000000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 4,
                        'valor' => '$ 2.500.000',
                        'bd' => 2500000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 5,
                        'valor' => '$ 3.000.000',
                        'bd' => 3000000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 6,
                        'valor' => '$ 3.500.000',
                        'bd' => 3500000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 7,
                        'valor' => '$ 4.000.000',
                        'bd' => 4000000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 8,
                        'valor' => '$ 4.500.000',
                        'bd' => 4500000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 9,
                        'valor' => '$ 5.000.000',
                        'bd' => 5000000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 10,
                        'valor' => '$ 6.000.000',
                        'bd' => 6000000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 11,
                        'valor' => '$ 7.000.000',
                        'bd' => 7000000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 12,
                        'valor' => '$ 8.000.000',
                        'bd' => 8000000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 13,
                        'valor' => '$ 9.000.000',
                        'bd' => 9000000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 14,
                        'valor' => '$ 10.000.000',
                        'bd' => 10000000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 15,
                        'valor' => '$ 11.000.000',
                        'bd' => 11000000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 16,
                        'valor' => '$ 12.000.000',
                        'bd' => 12000000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 17,
                        'valor' => '$ 13.000.000',
                        'bd' => 13000000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 18,
                        'valor' => '$ 14.000.000',
                        'bd' => 14000000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 19,
                        'valor' => '$ 15.000.000',
                        'bd' => 15000000,
                    ],
                    [
                        'campo' => 'sueldo',
                        'id' => 20,
                        'valor' => ' > $ 20.000.000',
                        'bd' => 100000000,
                    ],
                ],               
            ],
        ],
    ];

    // $user = User::where('tipo_user', 2)->first();
    // Auth::login($user);    

    
    // $token = csrf_token();
    // $sessionName = session()->getName();
    // $sessionValue = session()->getId();
    
    $response = $this
    // ->withHeaders(['X-CSRF-TOKEN' => $token, 'Content-Type' => 'application/json'])
    // ->withCookie($sessionName, $sessionValue)
    ->json('POST', '/buscar?page=1&number=14', $requestData);  
   
    $response->assertStatus(Response::HTTP_OK);  
    
    $data = $response->json();
    
    $this->assertCount(2, $data); 
    }
}
