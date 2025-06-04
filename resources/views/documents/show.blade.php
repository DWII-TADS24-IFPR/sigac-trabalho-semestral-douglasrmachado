<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Visualizar Documento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Detalhes do Documento</h3>
                        
                        <dl class="mt-4 space-y-4">
                            <div>
                                <dt class="font-medium">Título:</dt>
                                <dd>{{ $document->title }}</dd>
                            </div>

                            @if($document->description)
                                <div>
                                    <dt class="font-medium">Descrição:</dt>
                                    <dd>{{ $document->description }}</dd>
                                </div>
                            @endif

                            <div>
                                <dt class="font-medium">Status:</dt>
                                <dd>
                                    @if($document->isPending())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pendente
                                        </span>
                                    @elseif($document->isApproved())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Aprovado
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Rejeitado
                                        </span>
                                    @endif
                                </dd>
                            </div>

                            @if($document->feedback)
                                <div>
                                    <dt class="font-medium">Feedback:</dt>
                                    <dd>{{ $document->feedback }}</dd>
                                </div>
                            @endif

                            <div>
                                <dt class="font-medium">Enviado em:</dt>
                                <dd>{{ $document->created_at->format('d/m/Y H:i') }}</dd>
                            </div>

                            @if(auth()->user()->isTeacher())
                                <div>
                                    <dt class="font-medium">Enviado por:</dt>
                                    <dd>{{ $document->user->name }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Documento</h3>
                        <iframe src="{{ Storage::url($document->file_path) }}" class="w-full h-96 border border-gray-300 rounded"></iframe>
                    </div>

                    @if(auth()->user()->isTeacher() && $document->isPending())
                        <div class="mt-6 border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Avaliar Documento</h3>
                            
                            <form action="{{ route('documents.update', $document) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <div>
                                    <x-input-label for="status" :value="__('Status')" />
                                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="approved">Aprovar</option>
                                        <option value="rejected">Rejeitar</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('status')" />
                                </div>

                                <div>
                                    <x-input-label for="feedback" :value="__('Feedback')" />
                                    <textarea id="feedback" name="feedback" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('feedback') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('feedback')" />
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Enviar Avaliação') }}</x-primary-button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('documents.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Voltar para Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 