<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;

class DropdownDokumen extends Component
{
    public function selectTipeDok($tipeDok)
    {
        return redirect()->route('list-doc', ['tipeDok' => Str::slug($tipeDok)]);
    }

    public function render()
    {
        return view('livewire.dropdown-dokumen');
    }
}
