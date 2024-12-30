<!-- Modal view for displaying customer information using Tailwind CSS -->
<div class="p-6 space-y-6 rounded-lg shadow-2xl border-2 border-gray-200">
    <!-- Section: Informasi Dokumen -->
    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-800 border-b-2 pb-2 mb-4">Informasi Dokumen</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="border-2 p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Tipe Dokumen:</p>
                <p class="text-gray-500">{{ $record->tipeDok }}</p>
            </div>
            <div class="border-2 p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Jenis Artikel:</p>
                <p class="text-gray-500">{{ $record->jenisDok }}</p>
            </div>
            <div class="border-2 p-4 rounded-lg col-span-1 md:col-span-2">
                <p class="text-sm font-semibold text-gray-800">Judul Artikel:</p>
                <p class="text-gray-500">{{ $record->judul }}</p>
            </div>
            <!-- Kolom TEU Badan memenuhi lebar penuh modal -->
            <div class="border-2 p-4 rounded-lg col-span-1 md:col-span-2">
                <p class="text-sm font-semibold text-gray-800">T.E.U Badan:</p>
                <p class="text-gray-500">{{ $record->teuBadan }}</p>
            </div>
        </div>
    </div>

    <!-- Section: Detail Penerbit -->
    <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Detail Penerbit</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="border-2 p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Penerbit:</p>
                <p class="text-gray-500">{{ $record->penerbit }}</p>
            </div>
            <div class="border-2 p-4 rounded-lg">
                <p class="text-sm font-semibold text-gray-800">Tahun Terbit:</p>
                <p class="text-gray-500">{{ $record->tahunTerbit }}</p>
            </div>
        </div>

        <!-- Section: Detail Penetapan -->
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Detail Penetapan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border-2 p-4 rounded-lg">
                    <p class="text-sm font-semibold text-gray-800">Tanggal Penetapan:</p>
                    <p class="text-gray-500">{{ \Carbon\Carbon::parse($record->tglPenetapan)->translatedFormat('d-F-Y') }}</p>
                </div>
                <div class="border-2 p-4 rounded-lg">
                    <p class="text-sm font-semibold text-gray-800">Tanggal Pengundangan:</p>
                    <p class="text-gray-500">{{ \Carbon\Carbon::parse($record->tglPengundangan)->translatedFormat('d-F-Y') }}</p>
                </div>
                <div class="border-2 p-4 rounded-lg col-span-1 md:col-span-2">
                    <p class="text-sm font-semibold text-gray-800">Tempat Penetapan:</p>
                    <p class="text-gray-500">{{ $record->tempatPenetapan }}</p>
                </div>
            </div>
        </div>

        <!-- Section: Detail Tambahan -->
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Detail Tambahan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border-2 p-4 rounded-lg col-span-1 md:col-span-2">
                    <p class="text-sm font-semibold text-gray-800">Sumber:</p>
                    <p class="text-gray-500">{{ $record->sumber }}</p>
                </div>
                <div class="border-2 p-4 rounded-lg col-span-1 md:col-span-2">
                    <p class="text-sm font-semibold text-gray-800">Subjek:</p>
                    <p class="text-gray-500">{{ $record->subjek }}</p>
                </div>
                <div class="border-2 p-4 rounded-lg col-span-1 md:col-span-2">
                    <p class="text-sm font-semibold text-gray-800">Bidang Hukum:</p>
                    <p class="text-gray-500">{{ $record->bidangHukum }}</p>
                </div>
                <div class="border-2 p-4 rounded-lg">
                    <p class="text-sm font-semibold text-gray-800">Bahasa:</p>
                    <p class="text-gray-500">{{ $record->bahasa }}</p>
                </div>
                <div class="border-2 p-4 rounded-lg">
                    <p class="text-sm font-semibold text-gray-800">Lokasi:</p>
                    <p class="text-gray-500">{{ $record->lokasi }}</p>
                </div>
                <!-- Kolom Lampiran -->
                <div class="border p-4 rounded-lg col-span-1 md:col-span-2">
                    <p class="text-sm font-semibold text-gray-800">Lampiran:</p>
                    <p class="text-gray-500">{{ $record->lampiran }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Section: View Dokumen PDF -->
    <!-- <div class="mb-4">
        <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Dokumen PDF</h2>
        <div class="w-full h-[600px] border p-4 rounded-lg">
            <iframe src="{{ asset('storage/pdf/' . $record->pdf_path) }}" width="100%" height="100%" frameborder="0"></iframe>
        </div>
    </div> -->
</div>