@foreach($produk as $p)
    <div class="produk-item kategori-{{ $p->kategori_id }}">
        <img src="/storage/{{ $p->foto_produk }}" alt="{{ $p->name_id }}">
        <h3>{{ $p['nama_produk_' . app()->getLocale()] }}</h3>
    </div>
@endforeach
