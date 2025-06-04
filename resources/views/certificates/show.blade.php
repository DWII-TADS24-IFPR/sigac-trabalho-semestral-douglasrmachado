<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Detalhes da Solicitação') }}
            </h2>
            @if($certificate->status === 'approved')
                <a href="{{ route('certificates.download', $certificate) }}" class="bg-green-500 text-gray-900 px-4 py-2 rounded-md text-sm font-medium hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Download do Certificado
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    <!-- Informações do Aluno (visível apenas para professores) -->
                    @if(auth()->user()->isTeacher())
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-300 mb-4">Informações do Aluno</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">Nome</dt>
                                    <dd class="mt-1 text-sm text-gray-300">{{ $certificate->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-300">{{ $certificate->user->email }}</dd>
                                </div>
                            </dl>
                        </div>
                    @endif

                    <!-- Status da Solicitação -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-300 mb-4">Status da Solicitação</h3>
                        <div class="bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                    @if($certificate->status === 'approved') bg-green-100 text-green-800
                                    @elseif($certificate->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    @if($certificate->status === 'pending')
                                        Pendente
                                    @elseif($certificate->status === 'approved')
                                        Aprovado
                                    @else
                                        Reprovado
                                    @endif
                                </span>
                                <span class="text-sm text-gray-400">
                                    Solicitado em {{ $certificate->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Detalhes da Solicitação -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-300 mb-4">Detalhes da Solicitação</h3>
                        <div class="bg-gray-700 rounded-lg p-4">
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-400 mb-2">Motivo da Solicitação</h4>
                                <p class="text-sm text-gray-300">{{ $certificate->reason }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-400 mb-2">Total de Horas</h4>
                                <p class="text-sm text-gray-300">{{ $certificate->total_hours }} horas</p>
                            </div>
                        </div>
                    </div>

                    <!-- Documentos Incluídos -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-300 mb-4">Documentos Incluídos</h3>
                        <div class="grid gap-4">
                            @foreach($certificate->documents as $document)
                                <div class="bg-gray-700 p-4 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-300">{{ $document->title }}</h4>
                                    <p class="text-sm text-gray-400 mt-1">{{ $document->hours }} horas</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Feedback do Professor -->
                    @if($certificate->feedback)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-300 mb-4">Feedback do Professor</h3>
                            <div class="bg-gray-700 rounded-lg p-4">
                                <p class="text-sm text-gray-300">{{ $certificate->feedback }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Formulário de Avaliação (apenas para professores e solicitações pendentes) -->
                    @if(auth()->user()->isTeacher() && $certificate->status === 'pending')
                        <div class="mt-8 pt-8 border-t border-gray-700">
                            <h3 class="text-lg font-medium text-gray-300 mb-4">Avaliar Solicitação</h3>
                            <form method="POST" action="{{ route('certificates.update', $certificate) }}" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <div>
                                    <x-input-label for="status" :value="__('Status')" class="text-gray-300" />
                                    <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                        <option value="approved">Aprovar</option>
                                        <option value="rejected">Rejeitar</option>
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="validated_hours" :value="__('Horas Validadas')" class="text-gray-300" />
                                    <x-text-input id="validated_hours" name="validated_hours" type="number" step="0.01" min="0" max="200" class="mt-1 block w-full bg-gray-700 text-gray-300" :value="old('validated_hours', min($certificate->total_hours, 200))" />
                                    <p class="mt-1 text-sm text-gray-400">
                                        Total de horas solicitadas: {{ $certificate->total_hours }}
                                        <br>
                                        <span class="text-yellow-400">Máximo permitido: 200 horas</span>
                                    </p>
                                </div>

                                <div>
                                    <x-input-label for="feedback" :value="__('Feedback')" class="text-gray-300" />
                                    <textarea id="feedback" name="feedback" rows="4" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required></textarea>
                                </div>

                                <div class="flex justify-end">
                                    <x-primary-button>
                                        {{ __('Enviar Avaliação') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- Botões de Ação -->
                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('certificates.index') }}" class="text-gray-400 hover:text-gray-300">
                            Voltar para Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 