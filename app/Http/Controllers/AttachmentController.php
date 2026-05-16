<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'max:10240'], // 10MB máximo por archivo
        ]);

        foreach ($request->file('files') as $file) {
            $path = $file->store('attachments', 'public');

            $task->attachments()->create([
                'filename' => $file->getClientOriginalName(),
                'path'     => $path,
                'mime_type' => $file->getMimeType(),
            ]);
        }

        return back()->with('success', 'Archivos subidos correctamente.');
    }

    public function destroy(Attachment $attachment)
    {
        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();

        return back()->with('success', 'Archivo eliminado correctamente.');
    }
}
