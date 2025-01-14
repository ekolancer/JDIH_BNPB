<div class="p-6 space-y-8">
    <!-- Section: Informasi Utama -->
    <div>
        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informasi Utama</h2>
        <table class="table-auto w-full border-collapse border border-gray-200">
            <tbody>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Tipe Dokumen</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->tipeDok }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Jenis Dokumen</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->jenisDok }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Judul</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->judul }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">TEU Badan</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->teuBadan }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Nomor</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->nomor }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Tahun</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->tahun }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Section: Detail Dokumen -->
    <div>
        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Detail Dokumen</h2>
        <table class="table-auto w-full border-collapse border border-gray-200">
            <tbody>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Singkatan</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->singkatan }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Tempat</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->tempat }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Penerbit</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->penerbit }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Tanggal Penetapan</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->tglPenetapan }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Tanggal Pengundangan</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->tglPengundangan }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Tanggal Dibacakan</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->tglDiBacakan }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Section: Metadata Tambahan -->
    <div>
        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Metadata Tambahan</h2>
        <table class="table-auto w-full border-collapse border border-gray-200">
            <tbody>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Subjek</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->subjek }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Sumber</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->sumber }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">ISBN / ISSN</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->isbn }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Bahasa</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->bahasa }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Lokasi</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->lokasi }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Bidang Hukum</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->bidangHukum }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Section: Lampiran dan Informasi Tambahan -->
    <div>
        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Lampiran dan Informasi Tambahan</h2>
        <table class="table-auto w-full border-collapse border border-gray-200">
            <tbody>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Lampiran</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->lampiran }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Abstrak</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->abstrak }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Download Count</td>
                    <td class="px-4 py-2 text-gray-800">{{ $record->download_count }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>