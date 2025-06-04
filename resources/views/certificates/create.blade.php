<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Solicitar Certificado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-100">
                    @if($approvedDocuments->isEmpty())
                        <div class="text-center">
                            <p class="text-gray-400 mb-4">Você ainda não possui documentos aprovados para solicitar um certificado.</p>
                            <a href="{{ route('documents.create') }}" class="inline-flex items-center px-4 py-2 bg-green-500 text-gray-900 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-green-400 focus:bg-green-400 active:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Enviar Documento
                            </a>
                        </div>
                    @else
                        <form method="POST" action="{{ route('certificates.store') }}" class="space-y-6">
                            @csrf

                            <div>
                                <x-input-label for="reason" :value="__('Motivo da Solicitação')" class="text-gray-300" />
                                <textarea id="reason" name="reason" rows="4" class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>{{ old('reason') }}</textarea>
                                <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label :value="__('Documentos Aprovados')" class="text-gray-300 mb-2" />
                                <div class="space-y-4">
                                    @foreach($approvedDocuments as $document)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="documents[]" value="{{ $document->id }}" id="document_{{ $document->id }}"
                                                class="rounded border-gray-600 bg-gray-700 text-green-500 shadow-sm focus:ring-green-500">
                                            <label for="document_{{ $document->id }}" class="ml-2 text-sm text-gray-300">
                                                {{ $document->title }} ({{ $document->hours }} horas)
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <x-input-error :messages="$errors->get('documents')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <a href="{{ route('certificates.index') }}" class="text-gray-400 hover:text-gray-300 mr-3">
                                    Cancelar
                                </a>
                                <x-primary-button>
                                    {{ __('Solicitar Certificado') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 