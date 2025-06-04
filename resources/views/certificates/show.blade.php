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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-300 mb-4">Informações da Solicitação</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">Status</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($certificate->status === 'approved') bg-green-100 text-green-800
                                            @elseif($certificate->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($certificate->status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">Data da Solicitação</dt>
                                    <dd class="mt-1 text-sm text-gray-300">{{ $certificate->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">Total de Horas</dt>
                                    <dd class="mt-1 text-sm text-gray-300">{{ $certificate->total_hours }} horas</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-400">Motivo da Solicitação</dt>
                                    <dd class="mt-1 text-sm text-gray-300">{{ $certificate->reason }}</dd>
                                </div>
                                @if($certificate->feedback)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-400">Feedback do Professor</dt>
                                        <dd class="mt-1 text-sm text-gray-300">{{ $certificate->feedback }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-300 mb-4">Documentos Incluídos</h3>
                            <div class="space-y-4">
                                @foreach($certificate->documents as $document)
                                    <div class="bg-gray-700 p-4 rounded-lg">
                                        <h4 class="text-sm font-medium text-gray-300">{{ $document->title }}</h4>
                                        <p class="text-sm text-gray-400 mt-1">{{ $document->hours }} horas</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

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
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 