<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-800 leading-tight">
            {{ __('Kelola Running Text') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="mb-4 flex justify-end">
                        <a href="{{ route('admin.running-texts.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Tambah Running Text Baru
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-red-50">
                                <tr>
                                    <th class="w-1/12 p-2 text-left text-xs font-medium text-red-700 uppercase">No</th>
                                    <th class="w-7/12 p-2 text-left text-xs font-medium text-red-700 uppercase">Teks</th>
                                    <th class="w-1/12 p-2 text-left text-xs font-medium text-red-700 uppercase">Status</th>
                                    <th class="w-2/12 p-2 text-left text-xs font-medium text-red-700 uppercase">Urutan</th>
                                    <th class="w-1/12 p-2 text-left text-xs font-medium text-red-700 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-list" class="divide-y divide-red-100">
                                @forelse ($runningTexts as $index => $text)
                                    <tr class="sortable-item" data-id="{{ $text->id }}">
                                        <td class="p-2 text-sm text-gray-700">{{ $index + 1 }}</td>
                                        <td class="p-2 text-sm text-gray-700">{{ $text->text }}</td>
                                        <td class="p-2 text-sm text-gray-700">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $text->active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $text->active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="p-2 text-sm text-gray-700">
                                            <div class="flex items-center">
                                                <span class="mr-2">{{ $text->order }}</span>
                                                <div class="cursor-move">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-2 text-sm text-gray-700">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.running-texts.edit', $text) }}" class="text-blue-600 hover:text-blue-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.running-texts.destroy', $text) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus running text ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-2 text-center text-sm text-gray-500">Tidak ada running text yang tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sortableList = document.getElementById('sortable-list');
            
            if (sortableList) {
                new Sortable(sortableList, {
                    animation: 150,
                    handle: '.cursor-move',
                    onEnd: function() {
                        const items = document.querySelectorAll('.sortable-item');
                        const order = Array.from(items).map(item => item.getAttribute('data-id'));
                        
                        // Update the display numbers
                        items.forEach((item, index) => {
                            item.querySelector('td:first-child').textContent = index + 1;
                        });
                        
                        // Send the new order to the server
                        fetch('{{ route("admin.running-texts.reorder") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ order: order })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Order updated successfully');
                            }
                        })
                        .catch(error => {
                            console.error('Error updating order:', error);
                        });
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout> 