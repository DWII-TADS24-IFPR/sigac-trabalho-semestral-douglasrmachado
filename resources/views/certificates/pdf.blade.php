<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificado de Horas Complementares</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .content {
            margin-bottom: 30px;
        }
        .signature {
            margin-top: 100px;
            text-align: center;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Certificado de Horas Complementares</h1>
    </div>

    <div class="content">
        <p>Certificamos que <strong>{{ $certificate->user->name }}</strong> completou um total de <strong>{{ $certificate->validated_hours }}</strong> horas de atividades complementares, conforme documentação apresentada e validada pela instituição.</p>

        <p>Documentos apresentados:</p>
        <ul>
            @foreach($certificate->documents as $document)
                <li>{{ $document->title }} - {{ $document->hours }} horas</li>
            @endforeach
        </ul>

        <p>Motivo da solicitação:</p>
        <p>{{ $certificate->reason }}</p>
    </div>

    <div class="signature">
        <p>_______________________________</p>
        <p>Coordenador do Curso</p>
    </div>

    <div class="footer">
        <p>Certificado emitido em {{ now()->format('d/m/Y') }}</p>
        <p>ID da solicitação: {{ $certificate->id }}</p>
    </div>
</body>
</html> 