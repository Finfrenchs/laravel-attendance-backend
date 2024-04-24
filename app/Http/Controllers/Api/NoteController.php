<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    // GET /api/notes
    public function index()
    {
        $notes = Note::all();
        return response()->json([
            'data' => $notes,
            'message' => 'Notes retrieved successfully',
            'status' => 'success'
        ], 200);
    }

    // GET /api/notes/{id}
    public function show($id)
    {
        $note = Note::findOrFail($id);
        return response()->json([
            'data' => $note,
            'message' => 'Note retrieved successfully',
            'status' => 'success'
        ], 200);
    }

    // POST /api/notes
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'title' => 'required|string',
            'note' => 'required|string',
        ]);

        $note = Note::create([
            'user_id' => $request->input('user_id'),
            'title' => $request->input('title'),
            'note' => $request->input('note')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Note created successfully',
            'data' => $note
        ], 201);
    }

    // PUT /api/notes/{id}
    public function update(Request $request, $id)
    {
        $note = Note::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string',
            'note' => 'sometimes|required|string',
        ]);

        $note->update($request->only('title', 'note'));

        return response()->json([
            'success' => true,
            'message' => 'Note updated successfully',
            'data' => $note
        ], 200);
    }

    // DELETE /api/notes/{id}
    public function destroy($id)
    {
        $note = Note::findOrFail($id);
        $note->delete();

        return response()->json([
            'success' => true,
            'message' => 'Note deleted successfully'
        ], 200);
    }
}
