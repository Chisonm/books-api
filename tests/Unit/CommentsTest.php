<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CommentsTest extends TestCase
{

    // get_all_comments
    public function test_get_all_comments()
    {
        $response = $this->json('GET', '/api/comments', [], ['Accept' => 'application/json']);
        $response
          ->assertStatus(200, $response->status())
          ->assertJson([
             'success' => true,
         ]);
    }

    // create_comment
    public function test_create_comment()
    {

        $book = Book::factory()->create();
        $params = [
            'book_id'   => $book->id,
            'name'   => 'daniel',
            'body'   => 'naso',
            'ip'   => '127.0.0.1',
        ];
        $response = $this->json('POST', '/api/comments', $params, ['Accept' => 'application/json']);
        $response
         ->assertStatus(201, $response->status())
         ->assertJson([
            'success' => true,
        ]);
    }

    // test_create_comment_with_invalid_params
    public function test_create_comment_with_invalid_params()
    {

        $params = [
            'book_id'   => 1,
            'name'   => 'daniel',
            'body'   => 'naso',
            'ip'   => '127.0.0.1'
        ];
        $response = $this->json('POST', '/api/comments', $params, ['Accept' => 'application/json']);
        $response
         ->assertStatus(422, $response->status())
         ->assertJson([
            'success' => false,
        ]);
    }

    // test_check_if_body_is_500_characters

    public function test_check_if_body_is_500_characters()
    {


        $book = Book::factory()->create();
        $params = [
            'book_id'   => $book->id,
            'name'   => 'daniel',
            'body'   => 'Lorem ipsum dolor daniel chisom sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibu',
            'ip'   => '127.0.0.1',
        ];
        $response = $this->json('POST', '/api/comments', $params, ['Accept' => 'application/json']);
        $response
         ->assertStatus(422, $response->status())
         ->assertJson([
            'success' => false,
        ]);
    }
}
