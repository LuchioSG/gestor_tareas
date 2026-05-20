<?php

use App\Models\Task;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

// Test 1: Siendo usuario X, al consultar ruta Y, 
// aseguro código 200 y se muestra un texto determinado.
test('authenticated user can view tasks index', function () {
    $this->actingAs($this->user)
        ->get(route('tasks.index'))
        ->assertStatus(200)
        ->assertSee('Mis Tareas');
});

// Test 2: Siendo usuario X, al enviar petición POST, 
// aseguro creación de registro en DB y redireccionamiento.
test('authenticated user can create a task', function () {
    $this->actingAs($this->user)
        ->post(route('tasks.store'), [
            'title'    => 'Tarea de prueba',
            'priority' => 'medium',
            'status'   => 'pending',
        ])
        ->assertRedirect(route('tasks.index'));

    $this->assertDatabaseHas('tasks', [
        'title'      => 'Tarea de prueba',
        'creator_id' => $this->user->id,
    ]);
});

// Test 3: Siendo usuario X, al enviar petición POST con información 
// incorrecta o faltante, asegurar error en validación.
test('task creation fails without title', function () {
    $this->actingAs($this->user)
        ->post(route('tasks.store'), [
            'title'    => '',
            'priority' => 'medium',
        ])
        ->assertSessionHasErrors('title');
});

// Test 4: Siendo usuario X, al enviar petición DELETE, 
// aseguro eliminación de registro en DB y redireccionamiento.
test('authenticated user can delete their own task', function () {
    $task = Task::factory()->create([
        'creator_id' => $this->user->id,
    ]);

    $this->actingAs($this->user)
        ->delete(route('tasks.destroy', $task))
        ->assertRedirect(route('tasks.index'));

    $this->assertSoftDeleted('tasks', ['id' => $task->id]);
});
