<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-800 leading-tight">
            {{ __('Detail Monitoring') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-600">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('user.monitorings.index') }}" class="text-red-600 hover:text-red-800">
                            &larr; Kembali ke Daftar Monitoring
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-2 text-red-700">Informasi Dasar</h3>
                            <table class="min-w-full">
                                <tr class="border-b border-red-100">
                                    <td class="py-2 pr-4 font-medium text-red-700">Nama Part</td>
                                    <td>{{ $monitoring->nama_part }}</td>
                                </tr>
                                <tr class="border-b border-red-100">
                                    <td class="py-2 pr-4 font-medium text-red-700">Type</td>
                                    <td>{{ $monitoring->type }}</td>
                                </tr>
                                <tr class="border-b border-red-100">
                                    <td class="py-2 pr-4 font-medium text-red-700">No Mold / Cavity</td>
                                    <td>{{ $monitoring->no_mol }}</td>
                                </tr>
                                <tr class="border-b border-red-100">
                                    <td class="py-2 pr-4 font-medium text-red-700">Background</td>
                                    <td>{{ $monitoring->background }}</td>
                                </tr>
                                <tr class="border-b border-red-100">
                                    <td class="py-2 pr-4 font-medium text-red-700">Part Masuk Lab</td>
                                    <td>{{ $monitoring->part_masuk_lab->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 pr-4 font-medium">Request</td>
                                    <td>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $monitoring->request == 'Measuring' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $monitoring->request ?? 'Belum ditetapkan' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-2 text-red-700">Status dan Jadwal</h3>
                            <table class="min-w-full">
                                <tr class="border-b border-red-100">
                                    <td class="py-2 pr-4 font-medium text-red-700">Status</td>
                                    <td>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($monitoring->status === 'pending') bg-red-100 text-red-800 
                                            @elseif($monitoring->status === 'approved') bg-green-100 text-green-800 
                                            @elseif($monitoring->status === 'rejected') bg-red-100 text-red-800 
                                            @elseif($monitoring->status === 'in_progress') bg-yellow-100 text-yellow-800
                                            @elseif($monitoring->status === 'completed') bg-green-100 text-green-800
                                            @endif">
                                            {{ ucfirst($monitoring->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr class="border-b border-red-100">
                                    <td class="py-2 pr-4 font-medium text-red-700">Kode Antrian</td>
                                    <td>{{ $monitoring->kode_antrian ?? 'Belum ditetapkan' }}</td>
                                </tr>
                                <tr class="border-b border-red-100">
                                    <td class="py-2 pr-4 font-medium text-red-700">Waktu Mulai</td>
                                    <td>{{ $monitoring->start ? $monitoring->start->format('d/m/Y H:i') : 'Belum dimulai' }}</td>
                                </tr>
                                <tr class="border-b border-red-100">
                                    <td class="py-2 pr-4 font-medium text-red-700">Waktu Selesai</td>
                                    <td>{{ $monitoring->finish ? $monitoring->finish->format('d/m/Y H:i') : 'Belum selesai' }}</td>
                                </tr>
                                <tr class="border-b border-red-100">
                                    <td class="py-2 pr-4 font-medium text-red-700">Catatan Admin</td>
                                    <td>{{ $monitoring->catatan ?? 'Tidak ada catatan' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($monitoring->isPending())
                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('user.monitorings.edit', $monitoring->id) }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                            Edit Data
                        </a>
                        
                        <form method="POST" action="{{ route('user.monitorings.destroy', $monitoring->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md">
                                Hapus Data
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 