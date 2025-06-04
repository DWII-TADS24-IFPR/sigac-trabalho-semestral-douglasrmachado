<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = auth()->user()->isTeacher()
            ? Document::with('user')->latest()->get()
            : auth()->user()->documents()->latest()->get();

        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->isStudent()) {
            return redirect()->route('documents.index')
                ->with('error', 'Apenas alunos podem enviar documentos.');
        }

        return view('documents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isStudent()) {
            return redirect()->route('documents.index')
                ->with('error', 'Apenas alunos podem enviar documentos.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'required|mimes:pdf|max:10240', // max 10MB
        ]);

        $path = $request->file('document')->store('documents', 'public');

        auth()->user()->documents()->create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Documento enviado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        if (!auth()->user()->isTeacher() && auth()->id() !== $document->user_id) {
            return redirect()->route('documents.index')
                ->with('error', 'Você não tem permissão para ver este documento.');
        }

        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        if (!auth()->user()->isTeacher()) {
            return redirect()->route('documents.index')
                ->with('error', 'Apenas professores podem avaliar documentos.');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'feedback' => 'required|string',
        ]);

        $document->update([
            'status' => $request->status,
            'feedback' => $request->feedback,
        ]);

        return redirect()->route('documents.show', $document)
            ->with('success', 'Documento avaliado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        if (!auth()->user()->isTeacher() && auth()->id() !== $document->user_id) {
            return redirect()->route('documents.index')
                ->with('error', 'Você não tem permissão para excluir este documento.');
        }

        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Documento excluído com sucesso!');
    }
}
