<?php
// Menerima dua variabel: $product dan $customer
?>
<div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
    <h4 class="text-lg font-semibold mb-3 text-indigo-700">Tulis Ulasan Anda</h4>
    <form action="{{ route('customer.reviews.store', $product) }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="rating_{{ $product->id }}" class="block text-sm font-medium text-gray-700">Rating (1-5)</label>
            <select name="rating" id="rating_{{ $product->id }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 text-sm">
                <option value="">Pilih Rating</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}">{{ $i }} Bintang</option>
                @endfor
            </select>
            @error('rating') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-3">
            <label for="comment_{{ $product->id }}" class="block text-sm font-medium text-gray-700">Komentar (Opsional)</label>
            <textarea name="comment" id="comment_{{ $product->id }}" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 text-sm"></textarea>
            @error('comment') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-150 text-sm">
            Kirim Review
        </button>
    </form>
</div>