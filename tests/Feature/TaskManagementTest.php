<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_fetch_all_tasks()
    {

        $response = $this->getJson('/api/tasks');

        // Check if all tasks are returned and in the right format
        $response->assertStatus(200)
                 ->assertJsonStructure(['current_page', 'data', 'first_page_url', 'from', 'last_page', 'last_page_url', 'links',]);
    }

    /** @test */
    public function it_can_create_a_task()
    {
        // Dommy Task
        $taskData = [
            'title' => 'Finish project',
            'description' => 'Complete the project by the end of the week.',
        ];

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'title',
                     'description'
                 ]);
        
        // Check if the task actually exist in the database
        $this->assertDatabaseHas('tasks', $taskData);
    }

    /** @test */
    public function it_can_update_a_task()
    {
        // Arrange
        $task = Task::factory()->create();

        $updatedData = [
            'title' => 'Updated title',
            'description' => 'Updated description',
            'status' => 'completed',
        ];

        $response = $this->patchJson("/api/tasks/{$task->id}", $updatedData);

        $response->assertStatus(200)
                 ->assertJson($updatedData);

        $this->assertDatabaseHas('tasks', $updatedData);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        // Arrange
        $task = Task::factory()->create();

        // Act
        $response = $this->deleteJson("/api/tasks/{$task->id}");

        // Assert
        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
