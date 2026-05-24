<div class="nav-item dropdown">
    <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Dokumen Hukum</a>
    <div class="dropdown-menu m-0">
        @foreach($tipes as $tipe)
            <a href="#" wire:click.prevent="selectTipeDok('{{ $tipe->kode }}')" class="dropdown-item">{{ $tipe->nama }}</a>
        @endforeach
    </div>
</div>