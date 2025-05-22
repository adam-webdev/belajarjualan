@extends('layouts.layoutmaster')

@section('title', 'Payments Management')

@section('content')
<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Payments List</h4>
                    <div class="d-flex gap-2">
                        <select class="form-select" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="pending">Menunggu</option>
                            <option value="paid">Lunas</option>
                            <option value="failed">Gagal</option>
                            <option value="refunded">Dikembalikan</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="paymentsTable">
                            <thead>
                                <tr>
                                    <th>Nomor Pesanan</th>
                                    <th>Pelanggan</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Bukti Pembayaran</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $payment->order_id) }}" class="text-primary">
                                            #{{ $payment->order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $payment->order->user->name }}</td>
                                    <td>
                                        @if($payment->payment_method === 'bank_transfer')
                                            <span class="badge bg-info">Transfer Bank</span>
                                        @else
                                            <span class="badge bg-primary">E-Wallet</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td>
                                        @switch($payment->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Menunggu</span>
                                                @break
                                            @case('paid')
                                                <span class="badge bg-success">Lunas</span>
                                                @break
                                            @case('failed')
                                                <span class="badge bg-danger">Gagal</span>
                                                @break
                                            @case('refunded')
                                                <span class="badge bg-secondary">Dikembalikan</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($payment->payment_proof)
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#proofModal{{ $payment->id }}">
                                                Lihat Bukti
                                            </button>
                                        @else
                                            <span class="text-muted">Belum ada bukti</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @if($payment->status === 'pending')
                                                <button type="button" class="btn btn-sm btn-success" onclick="updateStatus('{{ $payment->id }}', 'paid')">
                                                    Tandai Lunas
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="updateStatus('{{ $payment->id }}', 'failed')">
                                                    Tandai Gagal
                                                </button>
                                            @endif
                                            @if($payment->status === 'paid')
                                                <button type="button" class="btn btn-sm btn-secondary" onclick="updateStatus('{{ $payment->id }}', 'refunded')">
                                                    Kembalikan
                                                </button>
                                            @endif
                                            @if($payment->status === 'failed')
                                                <form action="{{ route('admin.payments.delete-failed', $payment) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pembayaran dan pesanan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Payment Proof Modal -->
                                @if($payment->payment_proof)
                                <div class="modal fade" id="proofModal{{ $payment->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Payment Proof - Order #{{ $payment->order->order_number }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Warning Alert -->
                                                <div class="alert alert-warning mb-3">
                                                    <h6 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Peringatan Penting</h6>
                                                    <p class="mb-0">Mohon verifikasi pembayaran dengan memeriksa mutasi rekening bank atau riwayat transaksi e-wallet Anda. Jangan hanya mengandalkan bukti pembayaran yang diunggah karena bisa saja dimanipulasi.</p>
                                                </div>

                                                <!-- Payment Details -->
                                                <div class="mb-3">
                                                    <h6>Detail Pembayaran:</h6>
                                                    <p class="mb-1"><strong>Jumlah:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                                    <p class="mb-1"><strong>Metode:</strong> {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                                                    <p class="mb-1"><strong>Tanggal:</strong> {{ $payment->created_at->format('d M Y H:i') }}</p>
                                                    <p class="mb-1"><strong>Status:</strong>
                                                        @switch($payment->status)
                                                            @case('pending')
                                                                <span class="badge bg-warning">Menunggu</span>
                                                                @break
                                                            @case('paid')
                                                                <span class="badge bg-success">Lunas</span>
                                                                @break
                                                            @case('failed')
                                                                <span class="badge bg-danger">Gagal</span>
                                                                @break
                                                            @case('refunded')
                                                                <span class="badge bg-secondary">Dikembalikan</span>
                                                                @break
                                                        @endswitch
                                                    </p>
                                                </div>

                                                <!-- Payment Proof Image -->
                                                <div class="text-center">
                                                    <img src="{{ asset('storage/' . $payment->payment_proof) }}"
                                                         class="img-fluid rounded"
                                                         alt="Bukti Pembayaran"
                                                         style="max-height: 500px;">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = new simpleDatatables.DataTable("#paymentsTable");

    // Status filter
    $('#statusFilter').on('change', function() {
        const status = $(this).val();
        table.search(status);
    });
});

// Update payment status
function updateStatus(paymentId, status) {
    if (!confirm('Are you sure you want to update this payment status?')) {
        return;
    }

    $.ajax({
        url: `/admin/payments/${paymentId}/status`,
        method: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            status: status
        },
        success: function(response) {
            if (response.success) {
                Toastify({
                    text: "Payment status updated successfully",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#4fbe87",
                }).showToast();

                // Reload page after 1 second
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        },
        error: function(xhr) {
            Toastify({
                text: "Failed to update payment status",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "center",
                backgroundColor: "#ff6b6b",
            }).showToast();
        }
    });
}
</script>
@endsection