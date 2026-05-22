{{-- Título --}}
<div class="mb-5">
    <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
    <input type="text" name="title" value="{{ old('title', $task->title ?? '') }}"
            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

{{-- Descripción --}}
<div class="mb-5">
    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
    <textarea name="description" rows="3"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('description', $task->description ?? '') }}</textarea>
    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

{{-- Categoría, Prioridad y Status --}}
<div class="grid grid-cols-3 gap-4 mb-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
        <select name="category_id"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <option value="">Sin categoría</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $task->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad *</label>
        <select name="priority" required
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <option value="low" {{ old('priority', $task->priority ?? '') == 'low' ? 'selected' : '' }}>Baja</option>
            <option value="medium" {{ old('priority', $task->priority ?? 'medium') == 'medium' ? 'selected' : '' }}>Media</option>
            <option value="high" {{ old('priority', $task->priority ?? '') == 'high' ? 'selected' : '' }}>Alta</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
        <select name="status"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <option value="pending" {{ old('status', $task->status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pendiente</option>
            <option value="in_progress" {{ old('status', $task->status ?? '') == 'in_progress' ? 'selected' : '' }}>En progreso</option>
            <option value="completed" {{ old('status', $task->status ?? '') == 'completed' ? 'selected' : '' }}>Completada</option>
        </select>
    </div>
</div>

{{-- Fecha límite --}}
<div class="mb-5">
    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha límite</label>
    <input type="date" name="due_date"
            value="{{ old('due_date', isset($task) ? $task->due_date?->format('Y-m-d') : '') }}"
            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
    @error('due_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

{{-- Tags --}}
<div class="mb-5">
    <label class="block text-sm font-medium text-gray-700 mb-2">Etiquetas</label>
    <div class="flex flex-wrap gap-3">
        @foreach($tags as $tag)
            <label class="flex items-center gap-1.5 text-sm cursor-pointer">
                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                        {{ in_array($tag->id, old('tags', isset($task) ? $task->tags->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                {{ $tag->name }}
            </label>
        @endforeach
    </div>
    @error('tags') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

{{-- Usuarios asignados --}}
<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">Asignar a usuarios</label>
    <div class="flex flex-wrap gap-3">
        @foreach($users as $user)
            <label class="flex items-center gap-1.5 text-sm cursor-pointer">
                <input type="checkbox" name="assignees[]" value="{{ $user->id }}"
                        {{ in_array($user->id, old('assignees', isset($task) ? $task->assignees->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                {{ $user->name }}
            </label>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const titleInput = document.querySelector('input[name="title"]');
    const dueDateInput = document.querySelector('input[name="due_date"]');

    form.addEventListener('submit', function (e) {
        let valid = true;

        // Validar título
        if (titleInput.value.trim().length < 3) {
            showError(titleInput, 'El título debe tener al menos 3 caracteres.');
            valid = false;
        } else {
            clearError(titleInput);
        }

        // Validar fecha límite no sea pasada
        if (dueDateInput.value) {
            const today = new Date().toISOString().split('T')[0];
            if (dueDateInput.value < today) {
                showError(dueDateInput, 'La fecha límite no puede ser en el pasado.');
                valid = false;
            } else {
                clearError(dueDateInput);
            }
        }

        if (!valid) e.preventDefault();
    });

    // Limpiar error al escribir
    titleInput.addEventListener('input', () => clearError(titleInput));
    dueDateInput.addEventListener('change', () => clearError(dueDateInput));

    function showError(input, message) {
        clearError(input);
        input.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
        const error = document.createElement('p');
        error.className = 'text-red-500 text-xs mt-1 js-error';
        error.textContent = message;
        input.parentNode.appendChild(error);
    }

    function clearError(input) {
        input.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
        const error = input.parentNode.querySelector('.js-error');
        if (error) error.remove();
    }
});
</script>