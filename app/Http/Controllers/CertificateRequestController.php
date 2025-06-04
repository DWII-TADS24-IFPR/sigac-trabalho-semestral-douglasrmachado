<?php

namespace App\Http\Controllers;

use App\Models\CertificateRequest;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

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
        $approvedDocuments = Auth::user()->documents()
            ->where('status', 'approved')
            ->whereNotIn('id', function($query) {
                $query->select('document_id')
                    ->from('certificate_request_documents')
                    ->join('certificate_requests', 'certificate_requests.id', '=', 'certificate_request_documents.certificate_request_id')
                    ->where('certificate_requests.status', '!=', 'rejected');
            })
            ->get();

        return view('certificates.create', compact('approvedDocuments'));
    }

    public function store(Request $request)
    {
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
        $this->authorize('view', $certificate);
        return view('certificates.show', compact('certificate'));
    }

    public function update(Request $request, CertificateRequest $certificate)
    {
        $this->authorize('update', $certificate);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'feedback' => 'required|string|max:1000'
        ]);

        $certificate->update([
            'status' => $request->status,
            'feedback' => $request->feedback
        ]);

        return redirect()->route('certificates.index')
            ->with('success', 'Solicitação de certificado atualizada com sucesso!');
    }

    public function download(CertificateRequest $certificate)
    {
        $this->authorize('download', $certificate);

        if ($certificate->status !== 'approved') {
            return back()->with('error', 'Este certificado ainda não foi aprovado.');
        }

        $pdf = PDF::loadView('certificates.pdf', compact('certificate'));
        
        return $pdf->download('certificado-horas-complementares.pdf');
    }
} 