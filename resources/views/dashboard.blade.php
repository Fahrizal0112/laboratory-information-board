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
                                        <th class="p-2 text-left text-xs font-medium text-red-700 uppercase">No Order</th>
                                        <th class="p-2 text-left text-xs font-medium text-red-700 uppercase">No Mold / Cavity</th>
                                        <th class="p-2 text-left text-xs font-medium text-red-700 uppercase">Request</th>
                                        <th class="p-2 text-left text-xs font-medium text-red-700 uppercase">Masuk Lab</th>
                                        <th class="p-2 text-left text-xs font-medium text-red-700 uppercase">Start</th>
                                        <th class="p-2 text-left text-xs font-medium text-red-700 uppercase">Finish</th>
                                        <th class="p-2 text-left text-xs font-medium text-red-700 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="monitoring-data" class="divide-y divide-red-100 fade-element">
                                    @if($approvedMonitorings->isEmpty())
                                        <tr>
                                            <td colspan="8" class="p-2 text-center text-sm text-gray-500">Tidak ada data monitoring yang disetujui.</td>
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
            th:nth-child(3), td:nth-child(3) { width: 20%; }
            th:nth-child(4), td:nth-child(4) { width: 10%; }
            th:nth-child(5), td:nth-child(5) { width: 12%; }
            th:nth-child(6), td:nth-child(6) { width: 12%; }
            th:nth-child(7), td:nth-child(7) { width: 12%; }
            th:nth-child(8), td:nth-child(8) { width: 12%; }
            th:nth-child(9), td:nth-child(9) { width: 5%; }
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
            let allData = @json($approvedMonitorings->toArray());
            
            // Jumlah data per halaman
            const perPage = 5;
            
            // Hitung total halaman
            let totalPages = Math.ceil(allData.length / perPage) || 1;
            
            // Halaman saat ini
            let currentPage = 0;
            
            // Referensi ke tbody
            const tbody = document.getElementById('monitoring-data');
            
            // Flag untuk menandai apakah sedang dalam proses animasi
            let isAnimating = false;
            
            // Timer untuk rotasi halaman
            let pageRotationTimer = null;
            
            // Fungsi untuk menampilkan data berdasarkan halaman
            function showData(resetPage = false) {
                // Jika tidak ada data, jangan gunakan animasi
                if (allData.length === 0) {
                    return;
                }
                
                // Jika sedang dalam proses animasi, batalkan
                if (isAnimating) {
                    return;
                }
                
                isAnimating = true;
                
                // Reset halaman jika diminta
                if (resetPage) {
                    currentPage = 0;
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
                            
                            let statusLabel = monitoring.status;
                            let statusClass = '';
                            
                            if (monitoring.status === 'approved') {
                                statusLabel = 'Open';
                                statusClass = 'bg-gray-300 text-gray-800';
                            } else if (monitoring.status === 'on_queue') {  
                                statusLabel = 'On Queue';
                                statusClass = 'bg-blue-300 text-blue-800';
                            } else if (monitoring.status === 'in_progress') {
                                if (monitoring.request === 'Measuring') {
                                    statusLabel = 'In Progress Measuring';
                                } else if (monitoring.request === 'Testing') {
                                    statusLabel = 'In Progress Testing';
                                } else {
                                    statusLabel = 'In Progress';
                                }
                                statusClass = 'bg-yellow-300 text-yellow-800';
                            } else if (monitoring.status === 'completed') {  
                                statusLabel = 'Close';
                                statusClass = 'bg-green-300 text-green-800';
                            } else if (monitoring.status === 'approved_finish') {  
                                statusLabel = 'Approved & Finish';
                                statusClass = 'bg-green-300 text-green-800';
                            } else if (monitoring.status === 'pending') {
                                statusLabel = 'Pending';
                                statusClass = 'bg-red-300 text-red-800';
                            }
                            
                            const userName = monitoring.user ? monitoring.user.name : 'Tidak diketahui';
                            
                            let dept = monitoring.user ? monitoring.user.dept : 'DEPT';
                            if (dept === 'Quality') dept = 'Quality';
                            else if (dept === 'PPIC') dept = 'PPIC';
                            else if (dept === 'Engineering') dept = 'ENG';
                            else if (dept === 'Plant Engineering') dept = 'PE';
                            else if (dept === 'Robot') dept = 'Robot';
                            else if (dept === 'Produksi') dept = 'PR';
                            else if (dept === 'Mold and Material Development') dept = 'MAMD';
                            else if (dept === 'Health Care Unit') dept = 'HCU';
                            else if (dept === 'Purchasing') dept = 'PU';
                            else if (dept === 'New Project Development') dept = 'NPD';
                            
                            const npk = monitoring.user ? monitoring.user.npk : '0000';
                            
                            const createdDate = new Date(monitoring.created_at);
                            const year = createdDate.getFullYear().toString().slice(-2);
                            const month = String(createdDate.getMonth() + 1).padStart(2, '0');
                            const day = String(createdDate.getDate()).padStart(2, '0');
                            const dateStr = `${year}${month}${day}`;
                            
                            const uniqueId = String(monitoring.id).padStart(4, '0');
                            
                            const noOrder = `${dept}-${npk}/${dateStr}${uniqueId}`;
                            
                            tr.innerHTML = `
                                <td class="p-2 text-sm text-gray-900 border-r" title="${actualIndex + 1}">${actualIndex + 1}</td>
                                <td class="p-2 text-sm font-medium text-gray-900 border-r" title="${userName}">${userName}</td>
                                <td class="p-2 text-sm font-medium text-gray-900 border-r" title="${noOrder}">${noOrder}</td>
                                <td class="p-2 text-sm text-gray-900 border-r" title="${monitoring.no_mol}">${monitoring.no_mol}</td>
                                <td class="p-2 text-sm text-gray-900 border-r">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${monitoring.request === 'Measuring' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'}" title="${monitoring.request || '-'}">
                                        ${monitoring.request || '-'}
                                    </span>
                                </td>
                                <td class="p-2 text-sm text-gray-900 border-r" title="${formattedPartMasukLab}">${formattedPartMasukLab}</td>
                                <td class="p-2 text-sm text-gray-900 border-r" title="${formattedStart}">${formattedStart}</td>
                                <td class="p-2 text-sm text-gray-900 border-r" title="${formattedFinish}">${formattedFinish}</td>
                                <td class="p-2 text-sm text-gray-900">
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
                            <td colspan="8" class="p-2 text-center text-sm text-gray-500">Tidak ada data monitoring yang disetujui.</td>
                        `;
                        tbody.appendChild(tr);
                    }
                    
                    document.getElementById('current-page').textContent = currentPage + 1;
                    document.getElementById('total-pages').textContent = totalPages;
                    document.getElementById('total-data').textContent = allData.length;
                    
                    currentPage = (currentPage + 1) % totalPages;
                    
                    setTimeout(() => {
                        tbody.classList.remove('fade-out');
                        isAnimating = false;
                        
                        if (allData.length <= perPage) {
                            return;
                        }
                        
                        if (pageRotationTimer) {
                            clearTimeout(pageRotationTimer);
                        }
                        
                        pageRotationTimer = setTimeout(showData, 10000); 
                    }, 300); 
                }, 300);
            }
            
            showData();

            function setupRunningText() {
                const runningTexts = @json($runningTexts->toArray());
                
                if (runningTexts.length === 0) {
                    runningTexts.push({
                        id: 0,
                        text: 'SEMANGAT KERJA!!',
                        active: true
                    });
                }
                
                const activeTexts = runningTexts.filter(text => text.active);
                
                if (activeTexts.length === 0) {
                    return;
                }
                
                const runningTextElement = document.getElementById('running-text-content');
                
                let currentTextIndex = 0;
                
                function showNextText() {
                    const currentText = activeTexts[currentTextIndex];
                    
                    runningTextElement.innerHTML = '';
                    runningTextElement.style.animation = 'none';
                    
                    void runningTextElement.offsetWidth;
                    
                    runningTextElement.textContent = currentText.text;
                    
                    const animationDuration = Math.max(8, currentText.text.length * 0.3); 
                    
                    runningTextElement.style.animation = `runningTextAnimation ${animationDuration}s linear 1`;
                    
                    runningTextElement.addEventListener('animationend', function handler() {
                        currentTextIndex = (currentTextIndex + 1) % activeTexts.length;
                        
                        runningTextElement.removeEventListener('animationend', handler);
                        
                        setTimeout(showNextText, 500);
                    }, { once: true });
                }
                
                showNextText();
            }
            
            setupRunningText();
            
            function checkForNewData() {
                fetch('/dashboard/data')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const newDataLength = data.approvedMonitorings.length;
                        const oldDataLength = allData.length;
                        
                        if (newDataLength !== oldDataLength) {
                            console.log(`Data baru terdeteksi: ${newDataLength} items (sebelumnya ${oldDataLength})`);
                            
                            allData = data.approvedMonitorings;
                            totalPages = Math.ceil(allData.length / perPage) || 1;
                            
                            if (pageRotationTimer) {
                                clearTimeout(pageRotationTimer);
                                pageRotationTimer = null;
                            }
                            
                            showData(true);
                        }
                    })
                    .catch(error => {
                        console.error('Error saat memeriksa data baru:', error);
                    });
            }
            
            setInterval(checkForNewData, 10000);
        });
    </script>
</x-app-layout>
