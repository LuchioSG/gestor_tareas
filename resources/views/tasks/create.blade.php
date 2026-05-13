<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva Tarea
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf

                    {{-- Título --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Título *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Categoría y Prioridad --}}
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Categoría</label>
                            <select name="category_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Sin categoría</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Prioridad *</label>
                            <select name="priority" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Baja</option>
                                <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Media</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Alta</option>
                            </select>
                        </div>
                    </div>

                    {{-- Fecha límite --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Fecha límite</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('due_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tags --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Etiquetas</label>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @foreach($tags as $tag)
                                <label class="flex items-center gap-1 text-sm">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                            {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                                    {{ $tag->name }}
                                </label>
                            @endforeach
                        </div>
                        @error('tags') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Usuarios asignados --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Asignar a usuarios</label>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @foreach($users as $user)
                                <label class="flex items-center gap-1 text-sm">
                                    <input type="checkbox" name="assignees[]" value="{{ $user->id }}"
                                            {{ in_array($user->id, old('assignees', [])) ? 'checked' : '' }}>
                                    {{ $user->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('tasks.index') }}"
                            class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                            Crear Tarea
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>