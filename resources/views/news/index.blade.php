<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Berita - SIWALIMA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">

    <nav class="bg-green-800 p-4 text-white">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ route('home') }}" class="font-bold text-xl flex items-center gap-2">
                ⬅ Kembali ke Beranda
            </a>
        </div>
    </nav>

    <div class="bg-white shadow-sm py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Arsip Berita & Artikel</h1>
            <p class="text-gray-600">Informasi terkini seputar kegiatan dan kehutanan di Maluku.</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12 max-w-6xl">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($news as $item)
                <div
                    class="bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-200 overflow-hidden flex flex-col h-full">
                    <div class="h-48 overflow-hidden bg-gray-200 relative">
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}"
                            class="w-full h-full object-cover hover:scale-105 transition duration-500">
                        <div class="absolute top-2 right-2 bg-green-600 text-white text-xs px-2 py-1 rounded">
                            {{ $item->published_at->format('d M Y') }}
                        </div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                            {{ $item->title }}
                        </h3>
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-1">
                            {{ Str::limit(strip_tags($item->content), 100) }}
                        </p>
                        <a href="#" class="text-green-600 font-medium text-sm hover:underline mt-auto">
                            Baca Selengkapnya &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-500">
                    Tidak ada berita ditemukan.
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $news->links() }}
        </div>

    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="container mx-auto px-6 py-2">

            <div class="text-center text-gray-500 py-4  border-t border-gray-800">
                &copy; 2025 SIWALIMA, Dikembangkan untuk pengelolaan data kehutanan di Provinsi Maluku.
            </div>
        </div>
    </footer>

</body>

</html>
