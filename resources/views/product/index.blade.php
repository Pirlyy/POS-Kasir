@extends('layouts.app')
@section('content_title', 'Data Produk')
@section('content')
    <div class="card">
        <div class="card-title">
            <h4 class="card-header">Daftar Product</h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-2">
                <x-product.form-product/>
            </div>
            <x-alert :errors="$errors" type="warning"/>
            <table class="table table-sm" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Sku</th>
                        <th>Nama Produk</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Active</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->nama_produk }}</td>
                        <td>Rp. {{ number_format($product->harga_beli_pokok) }}</td>
                        <td>Rp. {{ number_format($product->harga_jual) }}</td>
                        <td>{{ number_format($product->stok) }}</td>
                        <td>
                            <p class="badge {{ $product->is_active ? 'badge-succes' : 'badge danger' }}">
                                {{ $product->is_active ? 'Aktif' : 'Tidak Aktif'}}
                            </p>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <x-product.form-product :id="$product->id"/>
                                    <a href="{{ route('master-data.product.destroy', $product->id) }}" class="btn btn-danger mx-1" data-confirm-delete="true">
                                        <i class="fas fa-trash"></i>
                                    </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection