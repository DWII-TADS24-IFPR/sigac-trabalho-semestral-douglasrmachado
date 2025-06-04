<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Documentos') }}
            </h2>
            @if(auth()->user()->isStudent())
                <a href="{{ route('documents.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Enviar Novo Documento
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-200">
                    @if(session('success'))
                        <div class="bg-green-900 border border-green-600 text-green-100 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-900 border border-red-600 text-red-100 px-4 py-3 rounded relative mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-gray-800">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-gray-700 text-left text-sm leading-4 text-gray-400 uppercase tracking-wider">Título</th>
                                    @if(auth()->user()->isTeacher())
                                        <th class="px-6 py-3 border-b-2 border-gray-700 text-left text-sm leading-4 text-gray-400 uppercase tracking-wider">Aluno</th>
                                    @endif
                                    <th class="px-6 py-3 border-b-2 border-gray-700 text-left text-sm leading-4 text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-700 text-left text-sm leading-4 text-gray-400 uppercase tracking-wider">Data</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-700 text-left text-sm leading-4 text-gray-400 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $document)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-700">
                                            {{ $document->title }}
                                        </td>
                                        @if(auth()->user()->isTeacher())
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-700">
                                                {{ $document->user->name }}
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-700">
                                            @if($document->status === 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-900 text-yellow-200">
                                                    Pendente
                                                </span>
                                            @elseif($document->status === 'approved')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-900 text-green-200">
                                                    Aprovado
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-900 text-red-200">
                                                    Rejeitado
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-700">
                                            {{ $document->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-700">
                                            <a href="{{ route('documents.show', $document) }}" class="text-green-400 hover:text-green-300 mr-4">Ver</a>
                                            
                                            @if(auth()->user()->isTeacher() || auth()->id() === $document->user_id)
                                                <form action="{{ route('documents.destroy', $document) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Tem certeza que deseja excluir este documento?')">
                                                        Excluir
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-no-wrap border-b border-gray-700 text-center text-gray-400">
                                            Nenhum documento encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 