<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Task;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuarios
        $users = User::factory(5)->create();

        // Categorías
        $categories = Category::factory(4)->create();

        // Tags
        $tags = Tag::factory(8)->create();

        // Tareas
        Task::factory(20)->create([
            'creator_id' => fn() => $users->random()->id,
            'category_id' => fn() => $categories->random()->id,
        ])->each(function ($task) use ($users, $tags) {
            // Asignar 1-3 usuarios a cada tarea con rol
            $task->assignees()->attach(
                $users->random(rand(1, 3))->pluck('id'),
                ['role' => 'assignee']
            );

            // Asignar 1-3 tags a cada tarea
            $task->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')
            );
        });
    }
}
