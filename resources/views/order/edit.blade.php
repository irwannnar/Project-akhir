@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Edit Pesanan</h1>

        <form action="{{ route('orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="customer_name" class="block text-gray-700 mb-2">Nama Customer</label>
                <input type="text" name="customer_name" id="customer_name"
                    value="{{ old('customer_name', $order->customer_name) }}"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="service_id" class="block text-gray-700 mb-2">Jenis Layanan</label>
                <select name="service_id" id="service_id"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Pilih Layanan</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}"
                            {{ old('service_id', $order->service_id) == $service->id ? 'selected' : '' }}>
                            {{ $service->name }} ({{ $service->type }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="order_details" class="block text-gray-700 mb-2">Detail Pesanan</label>
                <textarea name="order_details" id="order_details" rows="3"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('order_details', $order->order_details) }}</textarea>
            </div>

            <div class="mb-4">
                <label for="quantity" class="block text-gray-700 mb-2">Jumlah</label>
                <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $order->quantity) }}"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-gray-700 mb-2">Status</label>
                <select name="status" id="status"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" {{ old('status', $order->status) == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="notes" class="block text-gray-700 mb-2">Catatan</label>
                <textarea name="notes" id="notes" rows="2"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes', $order->notes) }}</textarea>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">Kembali</a>
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    Update Pesanan
                </button>
            </div>
        </form>
    </div>
@endsection
