<?php

namespace App\Http\Controllers;

use App\Models\CertificateRequest;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateRequestController extends Controller
{
    public function index()
    {
        $requests = Auth::user()->isTeacher()
            ? CertificateRequest::with(['user', 'documents'])->latest()->get()
            : Auth::user()->certificateRequests()->with('documents')->latest()->get();

        return view('certificates.index', compact('requests'));
    }

    public function create()
    {
        if (!Auth::user()->isStudent()) {
            abort(403, 'Apenas alunos podem solicitar certificados.');
        }

        // Get all approved documents for the student, regardless if they were used in other certificates
        $approvedDocuments = Auth::user()->documents()
            ->where('status', 'approved')
            ->get();

        return view('certificates.create', compact('approvedDocuments'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isStudent()) {
            abort(403, 'Apenas alunos podem solicitar certificados.');
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
            'documents' => 'required|array|min:1',
            'documents.*' => 'exists:documents,id'
        ]);

        $documents = Document::whereIn('id', $request->documents)
            ->where('user_id', Auth::id())
            ->where('status', 'approved')
            ->get();

        $totalHours = $documents->sum('hours');

        $certificateRequest = CertificateRequest::create([
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'total_hours' => $totalHours
        ]);

        $certificateRequest->documents()->attach($documents->pluck('id'));

        return redirect()->route('certificates.index')
            ->with('success', 'Solicitação de certificado enviada com sucesso!');
    }

    public function show(CertificateRequest $certificate)
    {
        // Verifica se o usuário é professor ou se é o dono da solicitação
        if (!Auth::user()->isTeacher() && Auth::id() !== $certificate->user_id) {
            abort(403, 'Você não tem permissão para visualizar esta solicitação.');
        }

        $certificate->load(['user', 'documents']);
        return view('certificates.show', compact('certificate'));
    }

    public function update(Request $request, CertificateRequest $certificate)
    {
        if (!Auth::user()->isTeacher()) {
            abort(403, 'Apenas professores podem avaliar solicitações.');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'feedback' => 'required|string|max:1000',
            'validated_hours' => 'required_if:status,approved|numeric|min:0|max:200'
        ]);

        $certificate->update([
            'status' => $request->status,
            'feedback' => $request->feedback,
            'validated_hours' => $request->status === 'approved' ? $request->validated_hours : null
        ]);

        return redirect()->route('certificates.index')
            ->with('success', 'Solicitação de certificado atualizada com sucesso!');
    }

    public function download(CertificateRequest $certificate)
    {
        // Verifica se o usuário é professor ou se é o dono da solicitação
        if (!Auth::user()->isTeacher() && Auth::id() !== $certificate->user_id) {
            abort(403, 'Você não tem permissão para baixar este certificado.');
        }

        if ($certificate->status !== 'approved') {
            return back()->with('error', 'Este certificado ainda não foi aprovado.');
        }

        $pdf = Pdf::loadView('certificates.pdf', compact('certificate'));
        
        return $pdf->download('certificado-horas-complementares.pdf');
    }
} 