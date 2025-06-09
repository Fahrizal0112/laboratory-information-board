<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-red-800 leading-tight">
                {{ __('Data Monitoring Saya') }}
            </h2>
            <a href="{{ route('user.monitorings.create') }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                {{ __('Tambah Data Baru') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-600">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-red-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider">Nama Part</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider">No Mold / Cavity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider">Request</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider">Part Masuk Lab</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider">Kode Antrian</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-red-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-red-100">
                                @forelse ($monitorings as $index => $monitoring)
                                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-red-50' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $monitoring->nama_part }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $monitoring->type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $monitoring->no_mol }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $monitoring->request == 'Measuring' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $monitoring->request ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $monitoring->part_masuk_lab ? $monitoring->part_masuk_lab->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($monitoring->status === 'pending') bg-red-100 text-red-800 
                                                @elseif($monitoring->status === 'approved') bg-green-100 text-green-800 
                                                @elseif($monitoring->status === 'rejected') bg-red-100 text-red-800 
                                                @elseif($monitoring->status === 'in_progress') bg-yellow-100 text-yellow-800
                                                @elseif($monitoring->status === 'completed') bg-green-100 text-green-800
                                                @endif">
                                                {{ ucfirst($monitoring->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $monitoring->kode_antrian ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('user.monitorings.show', $monitoring->id) }}" class="text-red-600 hover:text-red-900">Lihat</a>
                                                
                                                @if($monitoring->isPending())
                                                    <a href="{{ route('user.monitorings.edit', $monitoring->id) }}" class="text-red-600 hover:text-red-900">Edit</a>
                                                    
                                                    <form method="POST" action="{{ route('user.monitorings.destroy', $monitoring->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data monitoring.</td>
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