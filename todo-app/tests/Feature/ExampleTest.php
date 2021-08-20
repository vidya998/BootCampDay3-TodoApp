<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $response = $this->json('DELETE', '/api/task/del', ["id" => 1]);
        $response -> assertStatus(200);
    }
    public function test_taskUpdationTest()
    {
        $response = $this->json('PUT', '/api/task/', ["id" => 2, "task_status"=> "progress"]);
        $response -> assertStatus(200);
    }
    public function test_taskFetchTest()
    {
        $response = $this->json('GET', '/api/task/fetch', ["id" => 2]);
        $response -> assertStatus(200);
    }
}
