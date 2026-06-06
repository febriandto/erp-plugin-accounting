<?php

namespace Plugins\accounting\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Plugins\accounting\Events\InvoiceCreated;
use Plugins\accounting\Events\InvoicePaid;
use Plugins\accounting\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::latest()->paginate(20);
        return view('accounting::invoices.index', compact('invoices'));
    }

    public function create()
    {
        $number = 'INV-' . date('Ymd') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT);
        return view('accounting::invoices.create', compact('number'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_number'      => 'required|unique:invoices,invoice_number',
            'client_name'         => 'required|string|max:255',
            'issue_date'          => 'required|date',
            'due_date'            => 'required|date|after_or_equal:issue_date',
            'items'               => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.qty'         => 'required|integer|min:1',
            'items.*.price'       => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::create($request->except('items'));
        $invoice->items()->createMany($request->items);

        // Fire event setiap kali invoice dibuat secara manual.
        // Plugin lain yang mendaftar ke InvoiceCreated akan otomatis dijalankan.
        Event::dispatch(new InvoiceCreated($invoice));

        return redirect()->route('accounting.invoices.index')
            ->with('success', 'Invoice berhasil dibuat.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('items');
        return view('accounting::invoices.show', compact('invoice'));
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('accounting.invoices.index')
            ->with('success', 'Invoice berhasil dihapus.');
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:draft,sent,paid,overdue',
        ]);

        $oldStatus = $invoice->status;
        $invoice->update(['status' => $request->status]);

        // Fire event InvoicePaid hanya ketika status BERUBAH menjadi 'paid'.
        // Kondisi ini penting — jangan fire kalau status sebelumnya sudah 'paid'
        // (mencegah listener dijalankan berkali-kali kalau user save ulang).
        if ($request->status === 'paid' && $oldStatus !== 'paid') {
            Event::dispatch(new InvoicePaid($invoice));
        }

        return back()->with('success', 'Status invoice diupdate.');
    }
}
