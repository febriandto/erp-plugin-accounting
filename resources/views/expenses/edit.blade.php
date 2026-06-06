@extends('layouts.app')

@section('title', 'Edit Expense')
@section('page-title', 'Edit Expense')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card anim-fadein">
            <div class="card-header"><h3 class="card-title">{{ $expense->title }}</h3></div>
            <form action="{{ route('accounting.expenses.update', $expense) }}" method="POST"
                  x-data="{ loading: false }" @submit="loading = true">
                @csrf @method('PUT')
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Title</label>
                        <input type="text" name="title" value="{{ old('title', $expense->title) }}"
                               class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label required">Amount (Rp)</label>
                            <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}"
                                   step="0.01" min="0.01"
                                   class="form-control @error('amount') is-invalid @enderror" required>
                            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Date</label>
                            <input type="date" name="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}"
                                   class="form-control @error('expense_date') is-invalid @enderror" required>
                            @error('expense_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Category</label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category', $expense->category) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="3"
                                  class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $expense->notes) }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="card-footer d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <span x-show="!loading"><i class="ti ti-check me-1"></i>Update</span>
                        <span x-show="loading" x-cloak><span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...</span>
                    </button>
                    <a href="{{ route('accounting.expenses.index') }}" class="btn">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
