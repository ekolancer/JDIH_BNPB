<div class="p-6 space-y-8 bg-gray-50">
    <!-- Section: Informasi Utama -->
    <div>
        <h2 class="text-lg font-semibold text-white bg-blue-600 p-2 rounded-md mb-4">Informasi Utama</h2>
        <table class="table-auto w-full border-collapse border border-gray-300 bg-white shadow-md rounded-md">
            <tbody>
                <tr class="bg-blue-50">
                    <td class="px-4 py-2 font-medium text-gray-700">Tipe Dokumen</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->tipeDok }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Jenis Dokumen</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->jenisDok }}</td>
                </tr>
                <tr class="bg-blue-50">
                    <td class="px-4 py-2 font-medium text-gray-700">Judul</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->judul }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">T.E.U Badan</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->teuBadan }}</td>
                </tr>
                <tr class="bg-blue-50">
                    <td class="px-4 py-2 font-medium text-gray-700">Nomor</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->nomor }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Tahun</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->tahun }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Section: Detail Dokumen -->
    <div>
        <h2 class="text-lg font-semibold text-white bg-green-600 p-2 rounded-md mb-4">Detail Dokumen</h2>
        <table class="table-auto w-full border-collapse border border-gray-300 bg-white shadow-md rounded-md">
            <tbody>
                <tr class="bg-green-50">
                    <td class="px-4 py-2 font-medium text-gray-700">Singkatan</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->singkatan }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Tempat</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->tempat }}</td>
                </tr>
                <tr class="bg-green-50">
                    <td class="px-4 py-2 font-medium text-gray-700">Penerbit</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->penerbit }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Tanggal Penetapan</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->tglPenetapan }}</td>
                </tr>
                <tr class="bg-green-50">
                    <td class="px-4 py-2 font-medium text-gray-700">Tanggal Pengundangan</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->tglPengundangan }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Tanggal Dibacakan</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->tglDiBacakan }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Section: Metadata Tambahan -->
    <div>
        <h2 class="text-lg font-semibold text-white bg-purple-600 p-2 rounded-md mb-4">Metadata Tambahan</h2>
        <table class="table-auto w-full border-collapse border border-gray-300 bg-white shadow-md rounded-md">
            <tbody>
                <tr class="bg-purple-50">
                    <td class="px-4 py-2 font-medium text-gray-700">Subjek</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->subjek }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Sumber</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->sumber }}</td>
                </tr>
                <tr class="bg-purple-50">
                    <td class="px-4 py-2 font-medium text-gray-700">ISBN / ISSN</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->isbn }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Bahasa</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->bahasa }}</td>
                </tr>
                <tr class="bg-purple-50">
                    <td class="px-4 py-2 font-medium text-gray-700">Lokasi</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->lokasi }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Bidang Hukum</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->bidangHukum }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Section: Lampiran dan Informasi Tambahan -->
    <div>
        <h2 class="text-lg font-semibold text-white bg-red-600 p-2 rounded-md mb-4">Lampiran dan Informasi Tambahan</h2>
        <table class="table-auto w-full border-collapse border border-gray-300 bg-white shadow-md rounded-md">
            <tbody>
                <tr class="bg-red-50">
                    <td class="px-4 py-2 font-medium text-gray-700">Lampiran</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->lampiran }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Abstrak</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->abstrak }}</td>
                </tr>
                <tr class="bg-red-50">
                    <td class="px-4 py-2 font-medium text-gray-700">Download Count</td>
                    <td class="px-4 py-2 text-gray-900">{{ $record->download_count }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
