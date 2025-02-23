<div class="nav-item dropdown">
    <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Dokumen Hukum</a>
    <div class="dropdown-menu m-0">
        @foreach(['Peraturan', 'Monografi', 'Artikel', 'Putusan'] as $tipe)
            <a href="#" wire:click.prevent="selectTipeDok('{{ $tipe }}')" class="dropdown-item">{{ $tipe }}</a>
        @endforeach
    </div>
</div>