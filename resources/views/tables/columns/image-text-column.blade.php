<?php

$profile_image = '';

$record = $getState();

if (!$record->profile_picture) {
    if (strtolower($record->jenis_kelamin) === 'laki-laki') {
        $profile_image = '/avatar_male.png';
    } else {
        $profile_image = '/avatar_female.png';
    }
} else {
    $profile_image = Storage::url($record->profile_picture);
}

?>

<div class="flex items-center gap-2 px-3 py-4">
    <img src="{{ ($profile_image) }}" class="w-12 h-12 border-2 border-gray-400 rounded-full" />
    <div class="grid gap-y-1">
        <p class="text-sm font-medium truncate text-primary">
            {{ $record->nama_lengkap }}
        </p>
        <p class="text-sm truncate">
            {{ $record->nik }}
        </p>
    </div>
</div>