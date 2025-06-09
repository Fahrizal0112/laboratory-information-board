<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-600">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 text-red-700">{{ __('Data Monitoring Laboratorium') }}</h3>
                    
                    <div class="w-full">
                        <div class="responsive-table-container">
                            <table class="min-w-full bg-white table-auto">
                                <thead class="bg-red-50">
                                    <tr>
                                        <th class="p-2 text-left text-xs font-medium text-red-700 uppercase">No</th>
                                        <th class="p-2 text-left text-xs font-medium text-red-700 uppercase">Pemohon</th>
                                        <th class="p-2 text-left text-xs font-medium text-red-700 uppercase">Nama Part</th>
                                        <th class="p-2 text-left text-xs font-medium text-red-700 uppercase">Type</th>
                                        <th class="p-2 text-left text-xs font-medium text-red-700 uppercase">No Mold / Cavity</th>
                                        <th class="p-2 text-left text-xs font-medium text-red-700 uppercase">Masuk Lab</th>
                                        <th class="p-2 text-left text-xs font-medium text-red-700 uppercase">Start</th>
                                        <th class="p-2 text-left text-xs font-medium text-red-700 uppercase">Finish</th>
                                        <th class="p-2 text-left text-xs font-medium text-red-700 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="monitoring-data" class="divide-y divide-red-100 fade-element">
                                    @if($approvedMonitorings->isEmpty())
                                        <tr>
                                            <td colspan="9" class="p-2 text-center text-sm text-gray-500">Tidak ada data monitoring yang disetujui.</td>
                                        </tr>
                                    @endif
                                    <!-- Data akan diisi oleh JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4 flex justify-between items-center">
                            <div class="text-sm text-red-600">
                                Total data: <span id="total-data" class="font-semibold">{{ $approvedMonitorings->count() }}</span>
                            </div>
                            <div class="text-sm text-red-600">
                                Menampilkan halaman: <span id="current-page" class="font-semibold">1</span> dari <span id="total-pages" class="font-semibold">{{ ceil($approvedMonitorings->count() / 5) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Running Text -->
            <div class="mt-6 bg-red-600 text-white py-3 px-4 shadow-md rounded-lg overflow-hidden">
                <div class="running-text-container">
                    <div id="running-text-content" class="running-text">
                        <!-- Akan diisi oleh JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .responsive-table-container {
            width: 100%;
            overflow-x: auto;
        }
        
        @media (min-width: 1024px) {
            .responsive-table-container {
                overflow-x: visible;
            }
            
            table {
                table-layout: fixed;
                width: 100%;
            }
            
            th, td {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            
            th:nth-child(1), td:nth-child(1) { width: 5%; }
            th:nth-child(2), td:nth-child(2) { width: 12%; }
            th:nth-child(3), td:nth-child(3) { width: 15%; }
            th:nth-child(4), td:nth-child(4) { width: 10%; }
            th:nth-child(5), td:nth-child(5) { width: 10%; }
            th:nth-child(6), td:nth-child(6) { width: 12%; }
            th:nth-child(7), td:nth-child(7) { width: 12%; }
            th:nth-child(8), td:nth-child(8) { width: 12%; }
            th:nth-child(9), td:nth-child(9) { width: 12%; }
        }
        
        @media (max-width: 1023px) {
            .responsive-table-container {
                overflow-x: auto;
            }
        }
        
        /* Running text styles - yang dimodifikasi */
        .running-text-container {
            width: 100%;
            overflow: hidden;
            position: relative;
            height: 2.5rem; /* Tinggi tetap untuk container */
        }
        
        .running-text {
            position: absolute;
            white-space: nowrap;
            font-size: 1.25rem;
            font-weight: 600;
            letter-spacing: 1px;
            animation: pulse 2s infinite;
            right: -100%; /* Mulai dari luar layar di kanan */
            animation: runningTextAnimation 15s linear infinite;
            animation-play-state: paused; /* Akan dijalankan oleh JavaScript */
        }
        
        @keyframes runningTextAnimation {
            0% {
                right: -100%;
            }
            100% {
                right: 100%;
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }
        
        /* Fade in/out animations tetap sama */
        .fade-element {
            transition: opacity 1s ease-in-out;
        }
        
        .fade-out {
            opacity: 0;
        }
        
        .fade-in {
            opacity: 1;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data monitoring dari server
            const allData = @json($approvedMonitorings->toArray());
            
            // Jumlah data per halaman
            const perPage = 5;
            
            // Hitung total halaman
            const totalPages = Math.ceil(allData.length / perPage) || 1;
            
            // Halaman saat ini
            let currentPage = 0;
            
            // Referensi ke tbody
            const tbody = document.getElementById('monitoring-data');
            
            // Fungsi untuk menampilkan data berdasarkan halaman
            function showData() {
                // Jika tidak ada data, jangan gunakan animasi
                if (allData.length === 0) {
                    return;
                }
                
                // Fade out
                tbody.classList.add('fade-out');
                
                // Tunggu animasi fade out selesai sebelum mengubah data
                setTimeout(() => {
                    // Hitung indeks awal dan akhir data yang akan ditampilkan
                    const startIndex = currentPage * perPage;
                    const endIndex = Math.min(startIndex + perPage, allData.length);
                    
                    // Ambil data untuk halaman saat ini
                    const dataToShow = allData.slice(startIndex, endIndex);
                    
                    // Bersihkan tbody
                    tbody.innerHTML = '';
                    
                    // Tambahkan data ke tbody
                    if (dataToShow.length > 0) {
                        dataToShow.forEach((monitoring, index) => {
                            const actualIndex = startIndex + index;
                            const tr = document.createElement('tr');
                            tr.className = index % 2 === 0 ? 'bg-white' : 'bg-red-50';
                            
                            // Format tanggal
                            let formattedPartMasukLab = '-';
                            if (monitoring.part_masuk_lab) {
                                const partMasukLabDate = new Date(monitoring.part_masuk_lab);
                                formattedPartMasukLab = `${String(partMasukLabDate.getDate()).padStart(2, '0')}/${String(partMasukLabDate.getMonth() + 1).padStart(2, '0')}/${partMasukLabDate.getFullYear()}`;
                            }
                            
                            // Format waktu start dan finish jika ada
                            let formattedStart = '-';
                            if (monitoring.start) {
                                const startDate = new Date(monitoring.start);
                                formattedStart = `${String(startDate.getDate()).padStart(2, '0')}/${String(startDate.getMonth() + 1).padStart(2, '0')}/${startDate.getFullYear()}`;
                            }
                            
                            let formattedFinish = '-';
                            if (monitoring.finish) {
                                const finishDate = new Date(monitoring.finish);
                                formattedFinish = `${String(finishDate.getDate()).padStart(2, '0')}/${String(finishDate.getMonth() + 1).padStart(2, '0')}/${finishDate.getFullYear()}`;
                            }
                            
                            // Status label dan warna
                            let statusLabel = monitoring.status;
                            let statusClass = '';
                            
                            if (monitoring.status === 'approved') {
                                statusLabel = 'Open';
                                statusClass = 'bg-gray-100 text-gray-800';
                            } else if (monitoring.status === 'in_progress') {
                                statusLabel = 'In Progress';
                                statusClass = 'bg-yellow-100 text-yellow-800';
                            } else if (monitoring.status === 'completed') {
                                statusLabel = 'Close';
                                statusClass = 'bg-green-100 text-green-800';
                            } else if (monitoring.status === 'pending') {
                                statusLabel = 'Pending';
                                statusClass = 'bg-red-100 text-red-800';
                            }
                            
                            // Pastikan user ada sebelum mencoba mengakses propertinya
                            const userName = monitoring.user ? monitoring.user.name : 'Tidak diketahui';
                            
                            tr.innerHTML = `
                                <td class="p-2 text-sm text-gray-700" title="${actualIndex + 1}">${actualIndex + 1}</td>
                                <td class="p-2 text-sm font-medium text-gray-900" title="${userName}">${userName}</td>
                                <td class="p-2 text-sm font-medium text-gray-900" title="${monitoring.nama_part}">${monitoring.nama_part}</td>
                                <td class="p-2 text-sm text-gray-700" title="${monitoring.type}">${monitoring.type}</td>
                                <td class="p-2 text-sm text-gray-700" title="${monitoring.no_mol}">${monitoring.no_mol}</td>
                                <td class="p-2 text-sm text-gray-700" title="${formattedPartMasukLab}">${formattedPartMasukLab}</td>
                                <td class="p-2 text-sm text-gray-700" title="${formattedStart}">${formattedStart}</td>
                                <td class="p-2 text-sm text-gray-700" title="${formattedFinish}">${formattedFinish}</td>
                                <td class="p-2 text-sm text-gray-700">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}" title="${statusLabel}">
                                        ${statusLabel}
                                    </span>
                                </td>
                            `;
                            
                            tbody.appendChild(tr);
                        });
                    } else {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td colspan="9" class="p-2 text-center text-sm text-gray-500">Tidak ada data monitoring yang disetujui.</td>
                        `;
                        tbody.appendChild(tr);
                    }
                    
                    // Update nomor halaman
                    document.getElementById('current-page').textContent = currentPage + 1;
                    
                    // Increment halaman untuk tampilan berikutnya
                    currentPage = (currentPage + 1) % totalPages;
                    
                    // Fade in
                    setTimeout(() => {
                        tbody.classList.remove('fade-out');
                    }, 50);
                    
                    // Jika tidak ada data atau hanya 1 halaman, jangan gunakan interval
                    if (allData.length <= perPage) {
                        return;
                    }
                    
                    // Set timeout untuk menampilkan halaman berikutnya
                    setTimeout(showData, 10000); // 10 detik
                }, 1000); // Tunggu 1 detik untuk animasi fade out
            }
            
            // Mulai menampilkan data
            showData();

            // Fungsi untuk running text satu per satu
            function setupRunningText() {
                // Ambil data running text dari PHP
                const runningTexts = @json($runningTexts->toArray());
                
                // Default text jika tidak ada data
                if (runningTexts.length === 0) {
                    runningTexts.push({
                        id: 0,
                        text: 'SEMANGAT KERJA!!',
                        active: true
                    });
                }
                
                // Filter hanya text yang aktif
                const activeTexts = runningTexts.filter(text => text.active);
                
                if (activeTexts.length === 0) {
                    return; // Tidak ada teks aktif, jangan tampilkan apapun
                }
                
                // Element untuk running text
                const runningTextElement = document.getElementById('running-text-content');
                
                // Index text yang sedang ditampilkan
                let currentTextIndex = 0;
                
                // Fungsi untuk menampilkan teks selanjutnya
                function showNextText() {
                    const currentText = activeTexts[currentTextIndex];
                    
                    // Reset animasi dengan menghapus element dan membuatnya kembali
                    runningTextElement.innerHTML = '';
                    runningTextElement.style.animation = 'none';
                    
                    // Force reflow
                    void runningTextElement.offsetWidth;
                    
                    // Set teks baru
                    runningTextElement.textContent = currentText.text;
                    
                    // Tentukan durasi animasi berdasarkan panjang teks
                    const animationDuration = Math.max(8, currentText.text.length * 0.3); // Minimal 8 detik, atau lebih jika teks panjang
                    
                    // Mulai animasi baru
                    runningTextElement.style.animation = `runningTextAnimation ${animationDuration}s linear 1`;
                    
                    // Ketika animasi selesai, tampilkan teks berikutnya
                    runningTextElement.addEventListener('animationend', function handler() {
                        // Increment index dan reset jika sudah di akhir
                        currentTextIndex = (currentTextIndex + 1) % activeTexts.length;
                        
                        // Hapus event listener ini untuk mencegah duplikasi
                        runningTextElement.removeEventListener('animationend', handler);
                        
                        // Panggil fungsi ini lagi untuk menampilkan teks berikutnya
                        setTimeout(showNextText, 500); // Tunggu 0.5 detik sebelum menampilkan teks berikutnya
                    }, { once: true });
                }
                
                // Mulai running text
                showNextText();
            }
            
            // Inisialisasi running text
            setupRunningText();
        });
    </script>
</x-app-layout>
