@extends('layouts.app')

@section('title', 'Expense Detail')
@section('page-title', 'Expense Detail')

@section('page-actions')
@can('accounting.manage')
@if($expense->status === 'draft')
<form action="{{ route('accounting.expenses.update-status', $expense) }}" method="POST" class="d-inline"
      x-data="{ loading: false }" @submit="loading = true">
    @csrf @method('PATCH')
    <input type="hidden" name="status" value="approved">
    <button type="submit" class="btn btn-success me-1">
        <span x-show="!loading"><i class="ti ti-check me-1"></i>Approve</span>
        <span x-show="loading" x-cloak><span class="spinner-border spinner-border-sm"></span></span>
    </button>
</form>
<form action="{{ route('accounting.expenses.update-status', $expense) }}" method="POST" class="d-inline"
      x-data="{ loading: false }" @submit="loading = true">
    @csrf @method('PATCH')
    <input type="hidden" name="status" value="rejected">
    <button type="submit" class="btn btn-danger me-1">
        <span x-show="!loading"><i class="ti ti-x me-1"></i>Reject</span>
        <span x-show="loading" x-cloak><span class="spinner-border spinner-border-sm"></span></span>
    </button>
</form>
@endif
<a href="{{ route('accounting.expenses.edit', $expense) }}" class="btn">
    <i class="ti ti-edit me-1"></i>Edit
</a>
@endcan
@endsection

@section('content')
@php $status = \Plugins\accounting\Models\Expense::$statuses[$expense->status]; @endphp
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card anim-fadein">
            <div class="card-header">
                <h3 class="card-title">{{ $expense->title }}</h3>
                <div class="card-options">
                    <span class="badge bg-{{ $status['color'] }}-lt">{{ $status['label'] }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="datagrid">
                    <div class="datagrid-item">
                        <div class="datagrid-title">Amount</div>
                        <div class="datagrid-content fw-bold fs-3">
                            Rp {{ number_format($expense->amount, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Category</div>
                        <div class="datagrid-content">
                            <span class="badge bg-azure-lt">{{ $expense->category }}</span>
                        </div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Date</div>
                        <div class="datagrid-content">{{ $expense->expense_date->format('d M Y') }}</div>
                    </div>
                    <div class="datagrid-item">
                        <div class="datagrid-title">Submitted by</div>
                        <div class="datagrid-content">{{ $expense->creator->name ?? '-' }}</div>
                    </div>
                    @if($expense->notes)
                    <div class="datagrid-item" style="grid-column: 1 / -1">
                        <div class="datagrid-title">Notes</div>
                        <div class="datagrid-content">{{ $expense->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('accounting.expenses.index') }}" class="btn btn-link text-muted">
                    <i class="ti ti-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
