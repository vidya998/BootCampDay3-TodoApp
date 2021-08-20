<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_taskInsertionTest()
    {
        $response = $this->json('POST', '/api/task', ["id" => 11, "task_name"=> "Shopping",
            "task_description"=> "Buy egg,milk", "task_status"=> "pending"]);
        $response -> assertStatus(200);
    }
    public function test_taskDeletionTest()
    {
        $response = $this->get('/');
        $this->json('DELETE', 'api/task/del/4', [])
             -> assertStatus(200);
    }
    public function test_taskUpdationTest()
    {
        $response = $this->json('PUT', '/api/task/', ["id" => 17, "task_status"=> "progress"]);
        $response -> assertStatus(200);
    }
    public function test_taskFetchTest()
    {
        $response = $this->json('GET', '/api/task/fetch/12',[]);
        $response -> assertStatus(200);
        $response->assertJsonStructure([
        '*' => [
        ]
    ]);
    }
}
