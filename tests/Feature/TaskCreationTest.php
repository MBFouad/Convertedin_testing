<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;

class TaskCreationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Run the database migrations
        Artisan::call('migrate');
    }
    /** @test */
    public function it_creates_a_task(): void
    {
        // Arrange: Create a user
        $user = User::factory()->create();

        // Act: Create a task
        $task = Task::factory()->create([
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'created_by' => $user->id,
            'assign_to' => $user->id,
        ]);

        // Assert: Verify the task was created
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'created_by' => $user->id,
            'assign_to' => $user->id,
        ]);

    }


    /** @test */
    public function it_assigns_a_task_to_a_user()
    {
        // Arrange: Create a user and a task
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'assign_to' => $user->id,
        ]);

        // Act: Assign the task to the user
        $task->assign_to = $user->id;
        $task->save();

        // Assert: Verify the task was assigned
        $this->assertEquals($task->assign_to, $user->id);
    }

    /** @test */
    public function it_increments_task_count_for_user()
    {
        // Arrange: Create a user
        $user = User::factory()->create();

        // Act: Create tasks and assign them to the user
        Task::factory()->count(3)->create([
            'assign_to' => $user->id,
        ]);

        // Assert: Verify the task count for the user
        $taskCount = Task::where('assign_to', $user->id)->count();
        $this->assertEquals(3, $taskCount);
    }
}
