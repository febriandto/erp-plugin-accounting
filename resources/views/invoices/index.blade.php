@extends('layouts.app')

@section('title', 'Invoices')
@section('page-title', 'Invoices')

@section('page-actions')
<a href="{{ route('accounting.invoices.create') }}" class="btn btn-primary">
    <i class="ti ti-plus me-1"></i>Add Invoice
</a>
@endsection

@section('content')
<div class="card anim-fadein">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Client</th>
                    <th>Issue Date</th>
                    <th>Due Date</th>
                    <th class="text-end">Total</th>
                    <th style="width:120px">Status</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody class="anim-stagger">
                @forelse($invoices as $invoice)
                @php
                    [$badgeColor, $badgeIcon] = match($invoice->status) {
                        'paid'    => ['success', 'ti-circle-check'],
                        'sent'    => ['blue',    'ti-send'],
                        'overdue' => ['danger',  'ti-alert-circle'],
                        default   => ['secondary','ti-pencil'],
                    };
                    $isAp = $invoice->source_type === 'purchase_order';
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('accounting.invoices.show', $invoice) }}"
                           class="fw-medium text-decoration-none">
                            {{ $invoice->invoice_number }}
                        </a>
                        @if($isAp)
                        <span class="badge bg-purple-lt ms-1" title="Auto-generated dari Purchase Order">AP</span>
                        @endif
                    </td>
                    <td>{{ $invoice->client_name }}</td>
                    <td class="text-muted small">{{ $invoice->issue_date->format('d M Y') }}</td>
                    <td class="text-muted small">
                        {{ $invoice->due_date->format('d M Y') }}
                        @if($invoice->status !== 'paid' && $invoice->due_date->isPast())
                        <span class="text-danger small ms-1"><i class="ti ti-alert-triangle"></i></span>
                        @endif
                    </td>
                    <td class="text-end fw-medium">
                        Rp {{ number_format($invoice->total, 0, ',', '.') }}
                    </td>
                    <td>
                        <span class="badge bg-{{ $badgeColor }}-lt text-{{ $badgeColor }}">
                            <i class="ti {{ $badgeIcon }} me-1"></i>{{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-ghost-secondary dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('accounting.invoices.show', $invoice) }}">
                                    <i class="ti ti-eye me-2"></i>Lihat Detail
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('accounting.invoices.destroy', $invoice) }}" method="POST"
                                      onsubmit="return confirm('Yakin hapus invoice ini?')">
                                    @csrf @method('DELETE')
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="ti ti-trash me-2"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="ti ti-file-invoice mb-2" style="font-size:2rem;display:block"></i>
                        Belum ada invoice.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($invoices->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <div class="text-muted small">
            Menampilkan {{ $invoices->firstItem() }}–{{ $invoices->lastItem() }} dari {{ $invoices->total() }} invoice
        </div>
        {{ $invoices->links() }}
    </div>
    @endif
</div>
@endsection
