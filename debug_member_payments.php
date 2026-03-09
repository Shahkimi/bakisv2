<?php

/**
 * Run from project root: php debug_member_payments.php
 * Prompts for no_kp and displays member + payments data.
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Member;

echo "\n  Enter no_kp (IC number): ";
$noKp = trim(fgets(STDIN));

if ($noKp === '') {
    echo "  No input. Exiting.\n";
    exit(1);
}

$member = Member::where('no_kp', $noKp)->first();

if (!$member) {
    echo "\n  No member found with no_kp = \"{$noKp}\"\n\n";
    exit(1);
}

echo "\n" . str_repeat('=', 60) . "\n";
echo "  MEMBER\n";
echo str_repeat('=', 60) . "\n\n";

foreach ($member->getAttributes() as $key => $value) {
    $display = $value === null ? 'NULL' : (string) $value;
    echo "  " . str_pad($key, 20) . " : " . $display . "\n";
}

$payments = $member->payments()->orderBy('tahun_bayar')->orderBy('id')->get();

echo "\n" . str_repeat('=', 60) . "\n";
echo "  PAYMENTS (" . $payments->count() . " record(s))\n";
echo str_repeat('=', 60) . "\n\n";

if ($payments->isEmpty()) {
    echo "  No payments for this member.\n\n";
    exit(0);
}

$columns = [
    'id', 'member_id', 'tahun_bayar', 'jumlah', 'jenis',
    'no_resit_transfer', 'no_resit_sistem', 'bukti_bayaran',
    'status', 'approved_by', 'approved_at', 'catatan_admin',
    'created_at', 'updated_at',
];

foreach ($payments as $index => $p) {
    echo "  --- Payment #" . ($index + 1) . " (id: {$p->id}) ---\n";
    foreach ($columns as $col) {
        $value = $p->getAttribute($col);
        if ($value instanceof \DateTimeInterface) {
            $value = $value->format('Y-m-d H:i:s');
        }
        $display = $value === null ? 'NULL' : (string) $value;
        echo "  " . str_pad($col, 18) . " : " . $display . "\n";
    }
    echo "\n";
}

echo "  Done.\n\n";