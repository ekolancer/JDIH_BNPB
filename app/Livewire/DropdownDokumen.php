<?php

namespace App\Livewire;

use Livewire\Component;

class DropdownDokumen extends Component
{
    public function selectTipeDok($tipeDok)
    {
        return redirect()->route('list-doc', ['tipeDok' => $tipeDok]);
    }

    public function render()
    {
        return view('livewire.dropdown-dokumen');
    }
}
