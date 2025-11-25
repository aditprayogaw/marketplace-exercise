<div class="bg-white shadow-xl overflow-hidden sm:rounded-lg">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $role == 'vendor' ? 'Toko' : 'Role' }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($users as $user)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $role == 'vendor' ? $user->store_name : ucfirst($role) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.users.edit', ['role' => $role, 'id' => $user->id]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 font-semibold">Edit</a>
                        
                        {{-- Otorisasi Hapus: Tidak boleh menghapus admin yang sedang login --}}
                        @if ($role != 'admin' || $user->id !== ($currentAdminId ?? 0))
                            <form action="{{ route('admin.users.destroy', ['role' => $role, 'id' => $user->id]) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus {{ ucfirst($role) }} ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 bg-transparent border-none cursor-pointer font-semibold">Hapus</button>
                            </form>
                        @else
                            <span class="text-gray-400 text-xs">Akun Anda</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>