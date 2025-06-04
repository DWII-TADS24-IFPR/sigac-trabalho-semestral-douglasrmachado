<?php

namespace App\Policies;

use App\Models\CertificateRequest;
use App\Models\User;

class CertificateRequestPolicy
{
    public function view(User $user, CertificateRequest $certificate)
    {
        // Professores podem ver todas as solicitações
        if ($user->isTeacher()) {
            return true;
        }

        // Alunos só podem ver suas próprias solicitações
        return $user->id === $certificate->user_id;
    }

    public function update(User $user, CertificateRequest $certificate)
    {
        // Apenas professores podem atualizar solicitações
        return $user->isTeacher();
    }

    public function download(User $user, CertificateRequest $certificate)
    {
        // Professores podem baixar qualquer certificado aprovado
        if ($user->isTeacher() && $certificate->status === 'approved') {
            return true;
        }

        // Alunos só podem baixar seus próprios certificados aprovados
        return $user->id === $certificate->user_id && $certificate->status === 'approved';
    }
} 