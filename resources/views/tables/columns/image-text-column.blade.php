<?php

$profile_image = '';

if (!$getRecord()->profile_picture) {
    if (strtolower($getRecord()->jenis_kelamin) === 'laki-laki') {
        $profile_image = '/avatar_male.png';
    } else {
        $profile_image = '/avatar_female.png';
    }
} else {
    $profile_image = Storage::url($getRecord()->profile_picture);
}

?>

<div class="flex items-center gap-2 px-3 py-4">
    <img src="{{ ($profile_image) }}" class="w-12 h-12 border-2 border-gray-400 rounded-full" />
    <div class="grid gap-y-1">
        <p class="text-sm font-medium truncate text-primary">
            {{ $getRecord()->nama_lengkap }}
        </p>
        <p class="text-sm truncate">
            {{ $getRecord()->nik }}
        </p>
    </div>
</div>
