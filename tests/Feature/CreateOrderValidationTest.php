<?php

namespace Tests\Feature;

use App\Modules\Product\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateOrderValidationTest extends TestCase
{
    
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->seed();
    }

    /**
     * @test
     * @dataProvider validationProvider
     *
     * @param $requestData
     * @param $inputKey
     * @param $validationErrorMessage
     */
    public function it_returns_validation_error($requestData, $inputKey, $validationErrorMessage)
    {
        if( ($requestData['products'][0]['product_id'] ?? null) == '{PRODUCT_ID}' )
        {
            $requestData['products'][0]['product_id'] = Product::first()->id;
        }
        
        $this->json(
            'post',
            '/api/orders',
            $requestData,
        )
        ->assertStatus(422)
        ->assertJson([
            'errors' => [
                $inputKey => [
                    $validationErrorMessage,
                ],
            ]
        ]);
    }

    /**
     * Data provider method that contain keys names and the relative
     * validation message after looping on each key and get the error message
     *
     * @return array
     */
    public static function validationProvider(): array
    {
        return [
            'Test [products.0.product_id] has ingredients stock available'          => [
                'request_data'             => [
                    'products' => [
                        [
                            'product_id' => '{PRODUCT_ID}',
                            'quantity' => '10000',
                        ]
                    ],
                ],
                'form_input'               => 'products.0.product_id',
                'validation_error_message' => 'No enough ingredients available to fulfill this product!',
            ],
            'Test [products] is [required]'          => [
                'request_data'             => [
                    'products' => [],
                ],
                'form_input'               => 'products',
                'validation_error_message' => 'The products field is required.',
            ],
            'Test [products] is [array]'          => [
                'request_data'             => [
                    'products' => 'test',
                ],
                'form_input'               => 'products',
                'validation_error_message' => 'The products field must be an array.',
            ],
            'Test [products.0.quantity] is [required]'          => [
                'request_data'             => [
                    'products' => [
                        [
                            'quantity' => ''
                        ]
                    ],
                ],
                'form_input'               => 'products.0.quantity',
                'validation_error_message' => 'The products.0.quantity field is required.',
            ],
            'Test [products.0.product_id] is [required]'          => [
                'request_data'             => [
                    'products' => [
                        [
                            'product_id' => ''
                        ]
                    ],
                ],
                'form_input'               => 'products.0.product_id',
                'validation_error_message' => 'The products.0.product_id field is required.',
            ],
            'Test [products.0.product_id] is [exist]'          => [
                'request_data'             => [
                    'products' => [
                        [
                            'product_id' => '22'
                        ]
                    ],
                ],
                'form_input'               => 'products.0.product_id',
                'validation_error_message' => 'The selected products.0.product_id is invalid.',
            ],
        ];
    }

}