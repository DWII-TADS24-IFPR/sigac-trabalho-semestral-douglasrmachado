<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ auth()->user()->isTeacher() ? 'Solicitações de Certificados' : 'Meus Certificados' }}
            </h2>
            @if(auth()->user()->isStudent())
                <a href="{{ route('certificates.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Nova Solicitação de Certificado
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
                                    @if(auth()->user()->isTeacher())
                                        <th class="px-6 py-3 border-b-2 border-gray-700 text-left text-sm leading-4 text-gray-400 uppercase tracking-wider">Aluno</th>
                                    @endif
                                    <th class="px-6 py-3 border-b-2 border-gray-700 text-left text-sm leading-4 text-gray-400 uppercase tracking-wider">Total de Horas</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-700 text-left text-sm leading-4 text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-700 text-left text-sm leading-4 text-gray-400 uppercase tracking-wider">Data</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-700 text-left text-sm leading-4 text-gray-400 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $request)
                                    <tr>
                                        @if(auth()->user()->isTeacher())
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-700">
                                                {{ $request->user->name }}
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-700">
                                            {{ $request->total_hours }} horas
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-700">
                                            @if($request->status === 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-900 text-yellow-200">
                                                    Pendente
                                                </span>
                                            @elseif($request->status === 'approved')
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
                                            {{ $request->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-700">
                                            <a href="{{ route('certificates.show', $request) }}" class="text-green-400 hover:text-green-300 mr-4">Detalhes</a>
                                            
                                            @if($request->status === 'approved')
                                                <a href="{{ route('certificates.download', $request) }}" class="text-blue-400 hover:text-blue-300">Download</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-no-wrap border-b border-gray-700 text-center text-gray-400">
                                            Nenhuma solicitação encontrada.
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