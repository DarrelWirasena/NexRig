<?php
return [
    'tax_rate' => env('TAX_RATE', 0.11),
    'bank_account_number' => env('BANK_ACCOUNT_NUMBER', '8830-1234-5678'), // Angka kedua adalah fallback/default jika env kosong
];