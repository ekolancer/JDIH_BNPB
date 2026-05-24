<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\RefTipeDokumen;

class DropdownDokumen extends Component
{
    public function selectTipeDok($tipeDok)
    {
        return redirect()->route('list-doc', ['tipeDok' => Str::slug($tipeDok)]);
    }

    public function render()
    {
        $tipes = RefTipeDokumen::active()->ordered()->get();
        return view('livewire.dropdown-dokumen', compact('tipes'));
    }
}
