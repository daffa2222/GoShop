<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Verifikasi Penjual
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($user->status === 'pending')
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">Akun Sedang Ditinjau</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Akun penjual Anda sedang ditinjau oleh tim admin kami.
                                Mohon tunggu persetujuan.
                            </p>
                        </div>
                    @elseif($user->status === 'rejected')
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">Pendaftaran Ditolak</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Maaf, pendaftaran penjual Anda ditolak.
                            </p>
                            <form method="POST" action="{{ route('seller.delete-account') }}" class="mt-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus akun?')">
                                    Hapus Akun
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>