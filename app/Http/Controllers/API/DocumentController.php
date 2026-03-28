<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // GET /api/documents
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $documents = Document::with('user')->paginate(15);
        } else {
            $documents = Document::where('user_id', $user->user_id)->paginate(15);
        }

        return response()->json($documents);
    }

    // POST /api/documents
    public function store(Request $request)
    {
        $request->validate([
            'file'           => 'required|file|max:10240',
            'document_type'  => 'sometimes|string|max:100',
            'application_id' => 'nullable|exists:applications,id',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        $document = Document::create([
            'user_id'        => $request->user()->user_id,
            'application_id' => $request->application_id,
            'original_name'  => $file->getClientOriginalName(),
            'file_path'      => $path,
            'file_type'      => $file->getClientMimeType(),
            'file_size'      => $file->getSize(),
            'document_type'  => $request->document_type ?? 'general',
        ]);

        return response()->json([
            'message'  => 'Document uploaded.',
            'document' => $document,
        ], 201);
    }

    // GET /api/documents/{document}
    public function show(Request $request, Document $document)
    {
        $user = $request->user();

        if (! $user->isAdmin() && $document->user_id !== $user->user_id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return response()->json($document);
    }

    // GET /api/documents/{document}/download
    public function download(Request $request, Document $document)
    {
        $user = $request->user();

        if (! $user->isAdmin() && $document->user_id !== $user->user_id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        if (! Storage::disk('public')->exists($document->file_path)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        return Storage::disk('public')->download($document->file_path, $document->original_name);
    }

    // DELETE /api/documents/{document}
    public function destroy(Request $request, Document $document)
    {
        $user = $request->user();

        if (! $user->isAdmin() && $document->user_id !== $user->user_id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return response()->json(['message' => 'Document deleted.']);
    }
}
