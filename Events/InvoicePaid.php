<?php

namespace Plugins\accounting\Events;

use Plugins\accounting\Models\Invoice;

/**
 * EVENT: InvoicePaid
 *
 * Di-fire oleh Accounting ketika status invoice berubah menjadi 'paid'.
 *
 * Contoh use-case yang mendengarkan event ini:
 * - Purchasing: update payment_status PO menjadi 'paid'
 * - Notifikasi: kirim email konfirmasi pembayaran
 * - Laporan: trigger refresh laporan keuangan
 *
 * Semua itu bisa dilakukan tanpa mengubah satu baris pun di Accounting.
 */
class InvoicePaid
{
    public function __construct(
        public readonly Invoice $invoice
    ) {}
}
