<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->deleteJson('/api/actions');
        $response
            ->assertStatus(200);

        $response = $this->get('/api/actions');
        $response
            ->assertStatus(200);

        $response = $this->postJson('/api/action', ['player' => 'X', 'row' => 0, 'col' => 0]);
        $response
            ->assertStatus(200);



        $response = $this->get('/api/actions');
        $response
            ->assertJson([
                [
                    'id'        =>  1,
                    'player'    => 'X',
                    'row'       =>  0,
                    'column'    =>  0
                ]
            ]);

        $response = $this->postJson('/api/action', ['player' => 'O', 'row' => 2, 'col' => 2]);
        $response
            ->assertStatus(200);

        $response = $this->postJson('/api/action', ['player' => 'X', 'row' => 0, 'col' => 1]);
        $response
            ->assertStatus(200);

        $response = $this->postJson('/api/action', ['player' => 'O', 'row' => 2, 'col' => 0]);
        $response
            ->assertStatus(200);

        $response = $this->get('/api/check');
        $response
            ->assertJson([

                'noWinner' =>  true,

            ]);

        $response = $this->postJson('/api/action', ['player' => 'X', 'row' => 0, 'col' => 2]);
        $response
            ->assertStatus(200);

        $response = $this->get('/api/check');
        $response
            ->assertJson(
                [
                    'winner' =>  'X',
                ]
            );
    }
}
