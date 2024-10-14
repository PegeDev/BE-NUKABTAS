<?php

if (!function_exists('convertIndonesianDate')) {
    function convertIndonesianDate($dateString)
    {
        // Definisikan array bulan dalam bahasa Indonesia
        $months = [
            'Januari' => '01',
            'Februari' => '02',
            'Maret' => '03',
            'April' => '04',
            'Mei' => '05',
            'Juni' => '06',
            'Juli' => '07',
            'Agustus' => '08',
            'September' => '09',
            'Oktober' => '10',
            'November' => '11',
            'Desember' => '12',
        ];

        // Pisahkan tempat dan tanggal
        $parts = explode(', ', $dateString);

        // Ambil bagian tanggal
        if (count($parts) === 2) {
            $tanggalBulanTahun = $parts[1];
        } else {
            return null; // Return null jika format tidak valid
        }

        // Pisahkan hari, bulan, dan tahun
        $dateParts = explode(' ', $tanggalBulanTahun);
        if (count($dateParts) === 3) {
            $day = $dateParts[0]; // Hari
            $month = $months[$dateParts[1]] ?? null; // Konversi bulan ke angka
            $year = $dateParts[2]; // Tahun
        } else {
            return null; // Return null jika format tidak valid
        }

        // Gabungkan dalam format Y-m-d
        if ($month) {
            $formattedDate = "$year-$month-$day"; // Format Y-m-d
            return $formattedDate;
        }

        return null;
    }
}
if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber($number)
    {
        // Hilangkan spasi, tanda kurung, dan karakter yang tidak dibutuhkan
        $number = preg_replace('/[^0-9]/', '', $number);

        // Jika nomor dimulai dengan '0', ubah menjadi '62'
        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }

        // Tambahkan tanda "+" jika tidak ada
        if (substr($number, 0, 2) !== '62') {
            $number = '62' . $number;
        }

        // Format menjadi +(62) xxx xxxx xxxxx
        if (strlen($number) > 10) {
            return '+(62) ' . substr($number, 2, 3) . ' ' . substr($number, 5, 4) . ' ' . substr($number, 9);
        }

        // Jika panjang nomor kurang dari format yang diinginkan
        return '+(62) ' . $number;
    }
}

if (!function_exists('maskEmail')) {
    function maskEmail($email)
    {
        // Pisahkan local part dan domain dari email
        list($localPart, $domain) = explode("@", $email);

        // Jika panjang localPart kurang dari atau sama dengan 2, kembalikan email asli
        if (strlen($localPart) <= 2) {
            return $email;
        }

        // Ambil huruf pertama dan terakhir dari localPart
        $firstChar = $localPart[0];
        $lastChar = $localPart[strlen($localPart) - 1];

        // Buat masked email dengan huruf pertama, tanda bintang, dan huruf terakhir
        $maskedLocalPart = $firstChar . str_repeat("*", 8) . $lastChar;

        // Gabungkan kembali localPart yang dimask dengan domain
        return $maskedLocalPart . "@" . $domain;
    }
}
