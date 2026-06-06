<?php

namespace Plugins\accounting\Listeners;

use Illuminate\Support\Facades\Event;
use Plugins\accounting\Events\InvoiceCreated;
use Plugins\accounting\Models\Invoice;

/**
 * LISTENER: CreateApInvoiceFromPo
 *
 * Mendengarkan event PurchaseOrderReceived dari Purchasing plugin.
 * Membuat AP Invoice (Accounts Payable) secara otomatis beserta line items-nya.
 */
class CreateApInvoiceFromPo
{
    public function handle(object $event): void
    {
        $po = $event->order;

        // Hindari double-create jika PO ini sudah punya AP invoice
        $exists = Invoice::where('source_type', 'purchase_order')
            ->where('source_id', $po->id)
            ->exists();

        if ($exists) {
            return;
        }

        // Load items PO — pastikan relasinya ter-load sebelum dipakai
        $po->loadMissing(['items', 'vendor']);

        // Buat header AP Invoice
        $invoice = Invoice::create([
            'invoice_number' => 'AP-' . date('Ymd') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT),
            'client_name'    => $po->vendor->name,
            'client_email'   => $po->vendor->email,
            'issue_date'     => now()->toDateString(),
            'due_date'       => now()->addDays(30)->toDateString(),
            'status'         => 'draft',
            'notes'          => "Auto-generated dari Purchase Order {$po->po_number}",
            'source_type'    => 'purchase_order',
            'source_id'      => $po->id,
        ]);

        // Salin setiap item PO ke invoice items.
        // Harga per unit di sini sudah include pajak supaya total invoice
        // sama persis dengan grand total PO.
        // Rumus: harga_dengan_pajak = unit_price × (1 + tax_rate / 100)
        foreach ($po->items as $poItem) {
            $priceWithTax = $poItem->unit_price * (1 + $poItem->tax_rate / 100);

            $invoice->items()->create([
                'description' => $poItem->item_name . ($poItem->description ? " — {$poItem->description}" : ''),
                'qty'         => $poItem->qty,
                'price'       => round($priceWithTax, 2),
            ]);
        }

        // Fire event InvoiceCreated agar Purchasing tahu AP invoice sudah ada
        Event::dispatch(new InvoiceCreated($invoice));
    }
}
