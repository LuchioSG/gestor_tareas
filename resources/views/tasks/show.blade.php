<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $task->title }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('tasks.edit', $task) }}"
                    class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 text-sm">
                    Editar
                </a>
                <a href="{{ route('tasks.index') }}"
                    class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 text-sm">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Info principal --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-500">Estado</p>
                        <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm
                            {{ $task->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $task->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $task->status === 'pending' ? 'bg-gray-100 text-gray-700' : '' }}">
                            {{ $task->status }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Prioridad</p>
                        <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm
                            {{ $task->priority === 'high' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-green-100 text-green-700' : '' }}">
                            {{ $task->priority }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Categoría</p>
                        <p class="mt-1 font-medium">{{ $task->category?->name ?? 'Sin categoría' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Fecha límite</p>
                        <p class="mt-1 font-medium">{{ $task->due_date?->format('d/m/Y') ?? 'Sin fecha' }}</p>
                    </div>
                </div>

                @if($task->description)
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Descripción</p>
                        <p class="mt-1 text-gray-800">{{ $task->description }}</p>
                    </div>
                @endif
            </div>

            {{-- Tags --}}
            @if($task->tags->count())
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-medium text-gray-700 mb-3">Etiquetas</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($task->tags as $tag)
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Asignados --}}
            @if($task->assignees->count())
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-medium text-gray-700 mb-3">Usuarios asignados</h3>
                    <div class="space-y-2">
                        @foreach($task->assignees as $assignee)
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-indigo-200 rounded-full flex items-center justify-center text-sm font-medium text-indigo-700">
                                    {{ substr($assignee->name, 0, 1) }}
                                </div>
                                <span class="text-sm text-gray-700">{{ $assignee->name }}</span>
                                <span class="text-xs text-gray-400">({{ $assignee->pivot->role }})</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Archivos adjuntos --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-medium text-gray-700 mb-3">Archivos adjuntos</h3>
                @if($task->attachments->count())
                    <ul class="space-y-2">
                        @foreach($task->attachments as $attachment)
                            <li class="flex items-center justify-between text-sm">
                                <span>{{ $attachment->filename }}</span>
                                <a href="{{ Storage::url($attachment->path) }}" 
                                    class="text-indigo-600 hover:underline" target="_blank">
                                    Descargar
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-400 text-sm">Sin archivos adjuntos aún.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>