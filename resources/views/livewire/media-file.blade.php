<!-- Modal untuk Melihat Lampiran -->
<div class="modal fade" id="lampiranModal" tabindex="-1" aria-labelledby="lampiranModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                @if($fileUrl)
                    <!-- Menampilkan file berdasarkan tipe -->
                    @if(str_ends_with($fileUrl, '.pdf'))
                        <iframe src="{{ Storage::url($fileUrl) }}" width="100%" height="600px"></iframe>
                    @elseif(in_array(pathinfo($fileUrl, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ Storage::url($fileUrl) }}" class="img-fluid" alt="Lampiran">
                    @else
                        <p>File tidak dapat ditampilkan.</p>
                    @endif
                @else
                    <p>File tidak ditemukan.</p>
                @endif
            </div>
        </div>
    </div>
</div>