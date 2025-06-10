<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Monitoring') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('admin.monitorings.index') }}" class="text-blue-600 hover:text-blue-900">
                            &larr; Kembali ke Daftar Monitoring
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Informasi Dasar</h3>
                            <table class="min-w-full">
                                <tr>
                                    <td class="py-2 pr-4 font-medium">Nama Part</td>
                                    <td>{{ $monitoring->nama_part }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 pr-4 font-medium">Type</td>
                                    <td>{{ $monitoring->type }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 pr-4 font-medium">No Mold / Cavity</td>
                                    <td>{{ $monitoring->no_mol }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 pr-4 font-medium">Background</td>
                                    <td>{{ $monitoring->background }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 pr-4 font-medium">Request</td>
                                    <td>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $monitoring->request == 'Measuring' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $monitoring->request ?? 'Belum ditetapkan' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2 pr-4 font-medium">Part Masuk Lab</td>
                                    <td>{{ $monitoring->part_masuk_lab ? $monitoring->part_masuk_lab->format('d/m/Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 pr-4 font-medium">Dibuat Oleh</td>
                                    <td>{{ $monitoring->user->name }} ({{ $monitoring->user->email }})</td>
                                </tr>
                            </table>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-2">Status dan Jadwal</h3>
                            <table class="min-w-full">
                                <tr>
                                    <td class="py-2 pr-4 font-medium">Status</td>
                                    <td>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($monitoring->status === 'pending') bg-yellow-100 text-yellow-800 
                                            @elseif($monitoring->status === 'approved') bg-green-100 text-green-800 
                                            @elseif($monitoring->status === 'rejected') bg-red-100 text-red-800 
                                            @elseif($monitoring->status === 'in_progress') bg-blue-100 text-blue-800
                                            @elseif($monitoring->status === 'completed') bg-purple-100 text-purple-800
                                            @endif">
                                            {{ ucfirst($monitoring->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-2 pr-4 font-medium">Kode Antrian</td>
                                    <td>{{ $monitoring->kode_antrian ?? 'Belum ditetapkan' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 pr-4 font-medium">Waktu Mulai</td>
                                    <td>{{ $monitoring->start ? $monitoring->start->format('d/m/Y H:i') : 'Belum dimulai' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 pr-4 font-medium">Waktu Selesai</td>
                                    <td>{{ $monitoring->finish ? $monitoring->finish->format('d/m/Y H:i') : 'Belum selesai' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 pr-4 font-medium">Catatan</td>
                                    <td>{{ $monitoring->catatan ?? 'Tidak ada catatan' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('admin.monitorings.edit', $monitoring->id) }}" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-md">
                            Edit Data
                        </a>
                        
                        @if($monitoring->isPending())
                            <button onclick="approveModal({{ $monitoring->id }})" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md">
                                Setujui
                            </button>
                            
                            <button onclick="rejectModal({{ $monitoring->id }})" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md">
                                Tolak
                            </button>
                        @endif
                    </div>

                    <!-- Tombol Arsip untuk data completed -->
                    @if($monitoring->isCompleted())
                        <form method="POST" action="{{ route('admin.monitorings.archive', $monitoring->id) }}" class="mt-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md" onclick="return confirm('Apakah Anda yakin ingin mengarsipkan data ini?')">
                                {{ __('Arsipkan Data') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Approve -->
    <div id="approveModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Setujui Monitoring</h3>
            <form id="approveForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label for="kode_antrian" class="block text-sm font-medium text-gray-700">Kode Antrian</label>
                    <input type="text" name="kode_antrian" id="kode_antrian" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeApproveModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">Setujui</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Reject -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Monitoring</h3>
            <form id="rejectForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan (Alasan Penolakan)</label>
                    <textarea name="catatan" id="catatan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Tolak</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function approveModal(id) {
            document.getElementById('approveForm').action = `/admin/monitorings/${id}/approve`;
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        function rejectModal(id) {
            document.getElementById('rejectForm').action = `/admin/monitorings/${id}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
</x-app-layout> 