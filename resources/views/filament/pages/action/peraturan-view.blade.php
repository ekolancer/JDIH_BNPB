<!-- Modal view for displaying customer information using Tailwind CSS -->
<div class="p-6 space-y-6 rounded-lg shadow-lg">
    <!-- Section: Informasi Dokumen -->
    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Informasi Dokumen</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Tipe Dokumen:</p>
                <p class="text-gray-500">{{ $record->tipeDok }}</p>
            </div>
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Jenis Dokumen:</p>
                <p class="text-gray-500">{{ $record->jenisDok }}</p>
            </div>
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Singkatan:</p>
                <p class="text-gray-500">{{ $record->singkatan }}</p>
            </div>
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Judul:</p>
                <p class="text-gray-500">{{ $record->judul }}</p>
            </div>
            <!-- Kolom TEU Badan memenuhi lebar penuh modal -->
            <div class="border p-4 rounded-lg col-span-1 md:col-span-2">
                <p class="text-sm font-semibold text-gray-800">T.E.U Badan:</p>
                <p class="text-gray-500">{{ $record->teuBadan }}</p>
            </div>
        </div>
    </div>

    <!-- Section: Detail Penetapan -->
    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Detail Penetapan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Nomor:</p>
                <p class="text-gray-500">{{ $record->nomor }}</p>
            </div>
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Tahun:</p>
                <p class="text-gray-500">{{ $record->tahun }}</p>
            </div>
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Tanggal Penetapan:</p>
                <p class="text-gray-500">{{ $record->tglPenetapan }}</p>
            </div>
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Tanggal Pengundangan:</p>
                <p class="text-gray-500">{{ $record->tglPengundangan }}</p>
            </div>
            <!-- Kolom Tempat Penetapan memenuhi lebar penuh modal -->
            <div class="border p-4 rounded-lg col-span-1 md:col-span-2">
                <p class="text-sm font-semibold text-gray-600">Tempat Penetapan:</p>
                <p class="text-gray-800">{{ $record->tempatPenetapan }}</p>
            </div>
        </div>
    </div>

    <!-- Section: Detail Tambahan -->
    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Detail Tambahan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Sumber:</p>
                <p class="text-gray-500">{{ $record->sumber }}</p>
            </div>
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Subjek:</p>
                <p class="text-gray-500">{{ $record->subjek }}</p>
            </div>
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Bidang Hukum:</p>
                <p class="text-gray-500">{{ $record->bidangHukum }}</p>
            </div>
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Bahasa:</p>
                <p class="text-gray-500">{{ $record->bahasa }}</p>
            </div>
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Lokasi:</p>
                <p class="text-gray-500">{{ $record->lokasi }}</p>
            </div>
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Penandatangan:</p>
                <p class="text-gray-500">{{ $record->penandatangan }}</p>
            </div>
            <!-- Kolom Status memenuhi lebar penuh modal -->
            <div class="border p-4 rounded-lg col-span-1 md:col-span-2">
                <p class="text-sm font-semibold text-gray-600">Status:</p>
                <p class="text-gray-800">{{ $record->status }}</p>
            </div>
        </div>
    </div>

    <!-- Section: Detail Pemerintahan -->
    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Metadata Tambahan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Urusan Pemerintahan:</p>
                <p class="text-gray-500">{{ $record->urusanPemerintahan }}</p>
            </div>
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Pemrakarsa:</p>
                <p class="text-gray-500">{{ $record->pemrakarsa }}</p>
            </div>
            <div class="border p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Peraturan Terkait:</p>
                <p class="text-gray-500">{{ $record->peraturanTerkait }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Tombol di Bawah Modal -->
<div class="mt-6 flex justify-end space-x-4">
    <!-- Route Edit Bawaan Filament -->
    <a href="{{ route('filament.resources.peraturan.edit', $record->id) }}" 
       class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        Edit
    </a>
    <button onclick="closeModal()" 
            class="px-4 py-2 bg-gray-300 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
        Tutup
    </button>
</div>


<script>
    function closeModal() {
        document.querySelector('.fixed').classList.add('hidden');
    }
</script>

<!-- {{-- resources/views/filament/resources/document-detail-modal.blade.php --}}
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Detail Dokumen: {{ $record->judul }}</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded-lg shadow-md">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Metadata</th>
                    <th class="py-2 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-600">Detail</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Tipe Dokumen</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->tipeDok }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Jenis Dokumen</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->jenisDok }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Singkatan</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->singkatan }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Judul</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->judul }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Tidak Badan</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->teuBadan }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Nomor</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->nomor }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Tahun</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->tahun }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Tempat Penetapan</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->tempatPenetapan }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Tanggal Penetapan</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->tglPenetapan }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Tanggal Pengundangan</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->tglPengundangan }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Sumber</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->sumber }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Subjek</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->subjek }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Bidang Hukum</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->bidangHukum }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Bahasa</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->bahasa }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Status</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->status }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Lokasi</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->lokasi }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Lampiran</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->lampiran }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Urusan Pemerintahan</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->urusanPemerintahan }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Pemrakarsa</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->pemrakarsa }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Penandatangan</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->penandatangan }}</td>
                </tr>
                <tr>
                    <td class="py-2 px-4 border-b border-gray-200">Peraturan Terkait</td>
                    <td class="py-2 px-4 border-b border-gray-200">{{ $record->peraturanTerkait }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div> -->
