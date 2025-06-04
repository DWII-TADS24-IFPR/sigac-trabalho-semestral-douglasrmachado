<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificado de Horas Complementares</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .logo {
            font-size: 36px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 20px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .content {
            font-size: 16px;
            line-height: 1.6;
            text-align: justify;
            margin-bottom: 40px;
        }
        .signature {
            text-align: center;
            margin-top: 60px;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #000;
            margin: 10px auto;
        }
        .documents {
            margin-top: 40px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        .document {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">SIGAC</div>
            <div class="title">Certificado de Horas Complementares</div>
        </div>

        <div class="content">
            Certificamos que <strong>{{ $certificate->user->name }}</strong> completou um total de 
            <strong>{{ $certificate->total_hours }} horas</strong> em atividades complementares, 
            conforme documentação apresentada e aprovada pela coordenação do curso.
        </div>

        <div class="documents">
            <h3>Documentos Aprovados:</h3>
            @foreach($certificate->documents as $document)
                <div class="document">
                    • {{ $document->title }} - {{ $document->hours }} horas
                </div>
            @endforeach
        </div>

        <div class="signature">
            <div class="signature-line"></div>
            <p>Coordenação do Curso</p>
        </div>

        <div style="text-align: center; margin-top: 40px; font-size: 14px;">
            Certificado emitido em {{ now()->format('d/m/Y') }}
        </div>
    </div>
</body>
</html> 