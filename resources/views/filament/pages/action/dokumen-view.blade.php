<div class="p-6 space-y-4">
    <!-- Table for Document Details -->
    <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse border border-gray-200">
            <tbody>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Tipe Dokumen</td>
                    <td class="px-4 py-2 text-gray-800" id="tipeDok">{{ $record->tipeDok }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Jenis Dokumen</td>
                    <td class="px-4 py-2 text-gray-800" id="jenisDok">{{ $record->jenisDok }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Judul</td>
                    <td class="px-4 py-2 text-gray-800" id="judul">{{ $record->judul }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">TEU Badan</td>
                    <td class="px-4 py-2 text-gray-800" id="teuBadan">{{ $record->teuBadan }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Nomor</td>
                    <td class="px-4 py-2 text-gray-800" id="nomor">{{ $record->nomor }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Tahun</td>
                    <td class="px-4 py-2 text-gray-800" id="tahun">{{ $record->tahun }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Singkatan</td>
                    <td class="px-4 py-2 text-gray-800" id="singkatan">{{ $record->singkatan }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Tempat</td>
                    <td class="px-4 py-2 text-gray-800" id="tempat">{{ $record->tempat }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Penerbit</td>
                    <td class="px-4 py-2 text-gray-800" id="penerbit">{{ $record->penerbit }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Tanggal Penetapan</td>
                    <td class="px-4 py-2 text-gray-800" id="tglPenetapan">{{ $record->tglPenetapan }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Tanggal Pengundangan</td>
                    <td class="px-4 py-2 text-gray-800" id="tglPengundangan">{{ $record->tglPengundangan }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Tanggal Dibacakan</td>
                    <td class="px-4 py-2 text-gray-800" id="tglDiBacakan">tglDiBacakan</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Nomor Panggil</td>
                    <td class="px-4 py-2 text-gray-800" id="nomorPanggil">{{ $record->nomorPanggil }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Cetakan Edisi</td>
                    <td class="px-4 py-2 text-gray-800" id="nomorPanggil">{{ $record->cetakanEdisi }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Deskripsi Fisik</td>
                    <td class="px-4 py-2 text-gray-800" id="nomorPanggil">{{ $record->deskripsiFisik }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Subjek</td>
                    <td class="px-4 py-2 text-gray-800" id="nomorPanggil">{{ $record->subjek }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Sumber</td>
                    <td class="px-4 py-2 text-gray-800" id="nomorPanggil">{{ $record->sumber }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">ISBN / ISSN</td>
                    <td class="px-4 py-2 text-gray-800" id="nomorPanggil">{{ $record->isbn }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Status</td>
                    <td class="px-4 py-2 text-gray-800" id="nomorPanggil">{{ $record->status }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Bahasa</td>
                    <td class="px-4 py-2 text-gray-800" id="nomorPanggil">{{ $record->bahasa }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Lokasi</td>
                    <td class="px-4 py-2 text-gray-800" id="nomorPanggil">{{ $record->lokasi }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Bidang Hukum</td>
                    <td class="px-4 py-2 text-gray-800" id="nomorPanggil">{{ $record->bidangHukum }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Lampiran</td>
                    <td class="px-4 py-2 text-gray-800" id="nomorPanggil">{{ $record->lampiran }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Abstrak</td>
                    <td class="px-4 py-2 text-gray-800" id="nomorPanggil">{{ $record->abstrak }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Nomor Induk Buku</td>
                    <td class="px-4 py-2 text-gray-800" id="nomorPanggil">{{ $record->nomorIndukBuku }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Penandatangan</td>
                    <td class="px-4 py-2 text-gray-800" id="nomorPanggil">{{ $record->penandatangan }}</td>
                </tr>
                <!-- Add additional rows for other fields -->
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-600">Download Count</td>
                    <td class="px-4 py-2 text-gray-800" id="download_count">-</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- Modal Footer -->
<div class="bg-gray-100 px-6 py-4 flex justify-end">
    <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 focus:outline-none" onclick="toggleModal()">Tutup</button>
</div>