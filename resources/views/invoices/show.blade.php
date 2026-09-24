@extends('layout')

@section('content')

<div class="container py-4">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-column flex-md-row
                justify-content-between align-items-md-center
                gap-3 mb-4">

        <div>
            <h4 class="fw-bold mb-1" style="color: #072743;">
                <i class="fas fa-file-invoice-dollar me-2 text-success"></i>
                Invoice
            </h4>

            <small class="text-muted">
                {{ $invoice->invoice_number }}
            </small>
        </div>

        <a href="{{ url()->previous() }}"
           class="btn btn-outline-primary d-flex align-items-center gap-1">

            <i class="fas fa-arrow-left me-1"></i>
            Back
        </a>

    </div>


    {{-- INVOICE CARD --}}
    <div class="card border-0 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header bg-white border-0
                    d-flex flex-column flex-md-row
                    justify-content-between align-items-md-center
                    gap-3 p-4">

            <div>
                <div class="small text-muted mb-1">
                    Invoice Number
                </div>

                <div class="fw-bold fs-5"
                     style="color: #072743;">

                    {{ $invoice->invoice_number }}

                </div>
            </div>


            {{-- STATUS --}}
            @php
                $status = strtolower($invoice->status ?? 'pending');
            @endphp

            @if($status === 'paid')

                <span class="badge bg-success-subtle text-success
                             border border-success-subtle px-3 py-2">

                    <i class="fas fa-check-circle me-1"></i>
                    Paid

                </span>

            @elseif($status === 'cancelled')

                <span class="badge bg-danger-subtle text-danger
                             border border-danger-subtle px-3 py-2">

                    <i class="fas fa-times-circle me-1"></i>
                    Cancelled

                </span>

            @else

                <span class="badge bg-warning-subtle text-warning-emphasis
                             border border-warning-subtle px-3 py-2">

                    <i class="fas fa-clock me-1"></i>
                    Pending

                </span>

            @endif

        </div>


        {{-- INVOICE INFORMATION --}}
        <div class="card-body p-4">

            <div class="row g-4 mb-4">

                {{-- STUDENT --}}
                <div class="col-12 col-md-6">

                    <div class="small text-muted mb-1">
                        Billed To
                    </div>

                    <div class="fw-semibold">
                        {{ $invoice->user?->name ?? 'Student' }}
                    </div>

                    @if($invoice->user?->email)
                        <small class="text-muted">
                            {{ $invoice->user->email }}
                        </small>
                    @endif

                </div>


                {{-- DATE --}}
                <div class="col-12 col-md-6">

                    <div class="small text-muted mb-1">
                        Invoice Date
                    </div>

                    <div class="fw-semibold">

                        {{ $invoice->invoice_date
                            ? \Carbon\Carbon::parse(
                                $invoice->invoice_date
                            )->format('M d, Y')
                            : '—'
                        }}

                    </div>

                </div>

            </div>


            {{-- ITEMS --}}
            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Description
                            </th>

                            <th class="text-center">
                                Hours
                            </th>

                            <th class="text-end">
                                Rate
                            </th>

                            <th class="text-end">
                                Amount
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($invoice->items as $item)

                            <tr>

                                <td>

                                    <div class="fw-semibold">
                                        {{ $item->description }}
                                    </div>

                                    @if($item->flight)
                                        <small class="text-muted">
                                            Flight:
                                            {{ $item->flight->name
                                                ?? $item->flight->flight_number
                                                ?? '—' }}
                                        </small>
                                    @endif

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        (float) $item->quantity,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ $invoice->currency }}
                                    {{ number_format(
                                        (float) $item->unit_price,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end fw-semibold">

                                    {{ $invoice->currency }}
                                    {{ number_format(
                                        (float) $item->amount,
                                        2
                                    ) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4"
                                    class="text-center text-muted py-4">

                                    No invoice items found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- TOTALS --}}
            <div class="row justify-content-end mt-4">

                <div class="col-12 col-md-5 col-lg-4">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Subtotal
                        </span>

                        <span>
                            {{ $invoice->currency }}
                            {{ number_format(
                                (float) $invoice->subtotal,
                                2
                            ) }}
                        </span>

                    </div>


                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Tax
                        </span>

                        <span>
                            {{ $invoice->currency }}
                            {{ number_format(
                                (float) $invoice->tax,
                                2
                            ) }}
                        </span>

                    </div>


                    <hr>


                    <div class="d-flex justify-content-between">

                        <span class="fw-bold"
                              style="color: #072743;">

                            Total

                        </span>

                        <span class="fw-bold fs-5"
                              style="color: #072743;">

                            {{ $invoice->currency }}
                            {{ number_format(
                                (float) $invoice->total,
                                2
                            ) }}

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
