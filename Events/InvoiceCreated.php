<?php

namespace Plugins\accounting\Events;

use Plugins\accounting\Models\Invoice;

/**
 * EVENT: InvoiceCreated
 *
 * Di-fire oleh Accounting ketika invoice baru berhasil dibuat,
 * baik secara manual oleh user maupun otomatis dari plugin lain (misalnya Purchasing).
 *
 * Plugin lain yang ingin tahu "ada invoice baru dibuat" cukup
 * mendaftarkan listener ke event ini — tanpa Accounting perlu tahu
 * siapa yang mendengarkan.
 */
class InvoiceCreated
{
    public function __construct(
        public readonly Invoice $invoice
    ) {}
}
