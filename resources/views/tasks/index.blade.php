<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mis Tareas</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $tasks->total() }} tareas en total</p>
            </div>
            <a href="{{ route('tasks.create') }}"
                class="flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Tarea
            </a>
        </div>
    </x-slot>

    <x-flash-messages />

    {{-- Stats rápidas --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <p class="text-sm text-gray-500">Pendientes</p>
            <p class="text-2xl font-bold text-yellow-500 mt-1">
                {{ $tasks->where('status', 'pending')->count() }}
            </p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <p class="text-sm text-gray-500">En progreso</p>
            <p class="text-2xl font-bold text-blue-500 mt-1">
                {{ $tasks->where('status', 'in_progress')->count() }}
            </p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <p class="text-sm text-gray-500">Completadas</p>
            <p class="text-2xl font-bold text-green-500 mt-1">
                {{ $tasks->where('status', 'completed')->count() }}
            </p>
        </div>
    </div>

    {{-- Lista de tareas --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        @forelse($tasks as $task)
            <div class="flex items-center justify-between p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors last:border-0">
                <div class="flex items-center gap-4 flex-1 min-w-0">

                    {{-- Indicador de prioridad --}}
                    <div class="w-1 h-10 rounded-full flex-shrink-0
                        {{ $task->priority === 'high' ? 'bg-red-500' : '' }}
                        {{ $task->priority === 'medium' ? 'bg-yellow-500' : '' }}
                        {{ $task->priority === 'low' ? 'bg-green-500' : '' }}">
                    </div>

                    <div class="min-w-0">
                        <h3 class="font-medium text-gray-900 truncate">{{ $task->title }}</h3>
                        <div class="flex items-center gap-2 mt-1 flex-wrap">

                            {{-- Status --}}
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                {{ $task->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $task->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $task->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                {{ $task->status === 'pending' ? 'Pendiente' : '' }}
                                {{ $task->status === 'in_progress' ? 'En progreso' : '' }}
                                {{ $task->status === 'completed' ? 'Completada' : '' }}
                            </span>

                            {{-- Categoría --}}
                            @if($task->category)
                                <span class="text-xs px-2 py-0.5 rounded-full text-white font-medium"
                                        style="background-color: {{ $task->category->color }}">
                                    {{ $task->category->name }}
                                </span>
                            @endif

                            {{-- Tags --}}
                            @foreach($task->tags->take(2) as $tag)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                                    {{ $tag->name }}
                                </span>
                            @endforeach

                            {{-- Fecha límite --}}
                            @if($task->due_date)
                                <span class="text-xs text-gray-400 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $task->due_date->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="flex items-center gap-1 ml-4 flex-shrink-0">
                    <a href="{{ route('tasks.show', $task) }}"
                        class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                        title="Ver">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </a>
                    <a href="{{ route('tasks.edit', $task) }}"
                        class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors"
                        title="Editar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                            onsubmit="return confirm('¿Eliminar tarea?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                title="Eliminar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-gray-500 font-medium">No tienes tareas aún</p>
                <p class="text-gray-400 text-sm mt-1">Crea tu primera tarea para empezar</p>
                <a href="{{ route('tasks.create') }}"
                    class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition-colors">
                    Crear tarea
                </a>
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $tasks->links() }}
    </div>

</x-app-layout>