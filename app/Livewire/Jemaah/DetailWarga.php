<?php

namespace App\Livewire\Jemaah;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;

class DetailWarga extends Component
{
    public $record,  $profile = null, $pengurus = "WARGA";

    public function mount()
    {
        // dd($this->record->profile_picture);
        if (!$this->record->profile_picture) {
            $this->profile = $this->record->jenis_kelamin === "laki-laki" ? "/avatar_male.png" : "/avatar_female.png";
        } else {
            $this->profile = Storage::url($this->record->profile_picture);
        }

        if ($this->record->kepengurusan_type) {
            $this->pengurus = Str::title($this->record->kepengurusan_type->jabatan . " " . $this->record->kepengurusan_type->posisi);
        }
    }



    public function render()
    {
        return view('livewire.jemaah.detail-warga');
    }
}
