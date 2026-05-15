<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mis Tareas
            </h2>
            <a href="{{ route('tasks.create') }}" 
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                + Nueva Tarea
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <x-flash-messages />
                    @forelse($tasks as $task)
                        <div class="border-b py-4 flex justify-between items-center">
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $task->title }}</h3>
                                <div class="flex gap-2 mt-1">
                                    @if($task->category)
                                        <span class="text-xs px-2 py-1 rounded-full text-white"
                                                style="background-color: {{ $task->category->color }}">
                                            {{ $task->category->name }}
                                        </span>
                                    @endif
                                    <span class="text-xs px-2 py-1 rounded-full
                                        {{ $task->priority === 'high' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $task->priority === 'low' ? 'bg-green-100 text-green-700' : '' }}">
                                        {{ $task->priority }}
                                    </span>
                                    <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">
                                        {{ $task->status }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('tasks.show', $task) }}" 
                                    class="text-indigo-600 hover:underline text-sm">Ver</a>
                                <a href="{{ route('tasks.edit', $task) }}" 
                                    class="text-yellow-600 hover:underline text-sm">Editar</a>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                        onsubmit="return confirm('¿Eliminar tarea?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline text-sm">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No tienes tareas aún.</p>
                    @endforelse

                    <div class="mt-4">
                        {{ $tasks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>