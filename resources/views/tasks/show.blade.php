<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ route('tasks.index') }}"
                   class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Creada por {{ $task->creator->name }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('tasks.edit', $task) }}"
                   class="flex items-center gap-2 px-4 py-2 bg-yellow-500 text-white text-sm rounded-lg hover:bg-yellow-600 transition-colors font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </a>
            </div>
        </div>
    </x-slot>

    <x-flash-messages />

    <div class="grid grid-cols-3 gap-6">

        {{-- Columna principal --}}
        <div class="col-span-2 space-y-6">

            {{-- Info principal --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">Estado</p>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            {{ $task->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $task->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $task->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                            {{ $task->status === 'pending' ? 'Pendiente' : '' }}
                            {{ $task->status === 'in_progress' ? 'En progreso' : '' }}
                            {{ $task->status === 'completed' ? 'Completada' : '' }}
                        </span>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">Prioridad</p>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            {{ $task->priority === 'high' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $task->priority === 'low' ? 'bg-green-100 text-green-700' : '' }}">
                            {{ $task->priority === 'high' ? 'Alta' : '' }}
                            {{ $task->priority === 'medium' ? 'Media' : '' }}
                            {{ $task->priority === 'low' ? 'Baja' : '' }}
                        </span>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-500 mb-1">Fecha límite</p>
                        <p class="text-sm font-medium text-gray-800">
                            {{ $task->due_date?->format('d/m/Y') ?? 'Sin fecha' }}
                        </p>
                    </div>
                </div>

                @if($task->description)
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Descripción</p>
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $task->description }}</p>
                    </div>
                @endif
            </div>

            {{-- Archivos adjuntos --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Archivos adjuntos</h3>

                <form action="{{ route('attachments.store', $task) }}" method="POST" enctype="multipart/form-data" class="mb-4">
                    @csrf
                    <div class="flex items-center gap-3">
                        <input type="file" name="files[]" multiple
                               class="text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 file:text-sm file:font-medium">
                        <button type="submit"
                                class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors font-medium whitespace-nowrap">
                            Subir
                        </button>
                    </div>
                    @error('files') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </form>

                @if($task->attachments->count())
                    <ul class="space-y-2">
                        @foreach($task->attachments as $attachment)
                            <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg text-sm">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                    <span class="text-gray-700">{{ $attachment->filename }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ asset('storage/' . $attachment->path) }}"
                                       class="text-indigo-600 hover:text-indigo-800 text-xs font-medium" target="_blank">
                                        Descargar
                                    </a>
                                    <form action="{{ route('attachments.destroy', $attachment) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar archivo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 text-xs font-medium">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-400 text-sm">Sin archivos adjuntos aún.</p>
                @endif
            </div>
        </div>

        {{-- Columna lateral --}}
        <div class="space-y-6">

            {{-- Categoría --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-3">Categoría</h3>
                @if($task->category)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm text-white font-medium"
                          style="background-color: {{ $task->category->color }}">
                        {{ $task->category->name }}
                    </span>
                @else
                    <p class="text-gray-400 text-sm">Sin categoría</p>
                @endif
            </div>

            {{-- Tags --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-3">Etiquetas</h3>
                @if($task->tags->count())
                    <div class="flex flex-wrap gap-2">
                        @foreach($task->tags as $tag)
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400 text-sm">Sin etiquetas</p>
                @endif
            </div>

            {{-- Asignados --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-3">Asignados</h3>
                @if($task->assignees->count())
                    <div class="space-y-2">
                        @foreach($task->assignees as $assignee)
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-sm font-semibold text-indigo-700">
                                    {{ substr($assignee->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $assignee->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $assignee->pivot->role }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400 text-sm">Sin usuarios asignados</p>
                @endif
            </div>

        </div>
    </div>

</x-app-layout>