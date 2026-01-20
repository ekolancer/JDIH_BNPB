<!-- Modal Lampiran Modern -->
<div class="modal fade lampiran-modal"
     id="lampiranModal"
     tabindex="-1"
     aria-hidden="true"
     data-bs-backdrop="true"
     data-bs-keyboard="true">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header lampiran-header">
                <div class="modal-title d-flex align-items gap-2">
                    <i class="fa-solid fa-file-lines mb-0"></i>
                    <h5 class="mb-0">Pratinjau Lampiran</h5>
                </div>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body lampiran-body">

                @if($fileUrl)
                    @php
                        $ext = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION));
                        $url = Storage::url($fileUrl);
                    @endphp

                    <div class="lampiran-preview">

                        {{-- PDF --}}
                        @if($ext === 'pdf')
                            <iframe
                                src="{{ $url }}"
                                class="lampiran-frame"
                                loading="lazy">
                            </iframe>

                        {{-- IMAGE --}}
                        @elseif(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                            <img src="{{ $url }}"
                                 class="lampiran-image"
                                 alt="Lampiran">

                        {{-- OTHER --}}
                        @else
                            <div class="lampiran-fallback">
                                <i class="fa-solid fa-file-circle-xmark"></i>
                                <p>File tidak dapat ditampilkan.</p>
                            </div>
                        @endif

                    </div>
                @else
                    <div class="lampiran-fallback">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <p>File tidak ditemukan.</p>
                    </div>
                @endif

            </div>

        </div>
    </div>
</div>
<!-- End of Modal Lampiran Modern -->