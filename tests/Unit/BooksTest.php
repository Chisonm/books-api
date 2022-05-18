<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\WithFaker;

class BooksTest extends TestCase
{
    // use RefreshDatabase;

    public function test_create_book()
    {

        $params = [
            'title'   => 'Uefa Champions League',
            'released'   => '2022-05-15',
            'authors'   => 'daniel',
            'country'   => 'naija',
            'publisher'    => 'daniel',
            'number_of_pages'    => '22',
            'isbn' => '899344',
        ];

       $response = $this->json('POST','/api/books', $params, ['Accept' => 'application/json']);
       $response
         ->assertStatus(201,$response->status())
         ->assertJson([
            'Success' => true,
        ]);
    }

    // test_create_book_with_invalid_params
    public function test_create_book_with_invalid_params()
    {
        $params = [
            'title'   => 'Uefa Champions League',
            'released'   => '2022-05-15',
            'authors'   => 'daniel',
            'country'   => 'naija',
            'publisher'    => 'daniel',
            'number_of_pages'    => '22',
            // 'isbn' => 33,
        ];

       $response = $this->json('POST','/api/books', $params, ['Accept' => 'application/json']);
       $response
         ->assertStatus(422,$response->status())
         ->assertJson([
            'Success' => false,
        ]);
    }

    // test get_all_books
    public function test_get_all_books()
    {
        $response = $this->json('GET','/api/books', [], ['Accept' => 'application/json']);
        // dd($response->status());
        if($response->status() == 404)
        {
            $response
            ->assertStatus(404,$response->status())
            ->assertJson([
                'Success' => false,
                ]);
        }
        $response
          ->assertStatus(200,$response->status())
          ->assertJson([
            'Success' => true,
        ]);
    }


}
