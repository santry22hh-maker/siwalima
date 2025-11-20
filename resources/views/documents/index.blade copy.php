<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAP KLHK Clone</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        /* Custom warna untuk background navbar (krem muda) */
        .bg-cream {
            background-color: #FFFBE6;
        }

        /* Custom gradient untuk hero section agar mirip */
        .hero-gradient {
            background: linear-gradient(90deg, #D97736 0%, #E68A45 50%, #D97736 100%);
        }

        /* Pola background abstrak (opsional/placeholder) */
        .hero-pattern {
            background-image: url('https://www.transparenttextures.com/patterns/cubes.png');
            /* Contoh pattern */
            opacity: 0.1;
        }
    </style>
</head>

<body class="bg-white">

    <div class="relative hero-gradient h-64 md:h-80 w-full flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 hero-pattern z-0"></div>
        <div class="absolute top-0 left-10 w-64 h-64 border-2 border-white opacity-10 rounded-full"></div>
        <div class="absolute bottom-0 right-20 w-96 h-96 border-2 border-white opacity-10 rounded-full"></div>

        <h1 class="relative z-10 text-3xl md:text-4xl font-bold text-white tracking-wide drop-shadow-md uppercase">
            Dokumen Elektronik
        </h1>
    </div>

    <div class="relative z-20 max-w-5xl mx-auto px-4 -mt-12">
        <div class="bg-white rounded-xl shadow-lg p-2 flex flex-col md:flex-row items-center h-auto md:h-20">

            <div class="flex-grow w-full h-full px-4 border-b md:border-b-0 md:border-r border-gray-200 py-3 md:py-0">
                <input type="text" placeholder="Cari dokumen disini"
                    class="w-full h-full text-gray-600 placeholder-gray-400 outline-none text-lg">
            </div>

            <div class="pt-24 container mx-auto p-4">
                <div class="mb-4">
                    <input type="text" placeholder="Cari dokumen disini" class="p-2 border rounded w-full md:w-1/2">
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    @foreach ($documents as $doc)
                        <div class="flex bg-white shadow rounded overflow-hidden">
                            <img src="{{ asset($doc->image_path) }}" alt="{{ $doc->title }}"
                                class="w-32 object-cover">
                            <div class="p-4 flex-1">
                                <h2 class="text-lg font-semibold">{{ $doc->title }}</h2>
                                <p class="text-gray-600 text-sm mb-2">{{ $doc->type }}</p>
                                <p class="text-gray-700 text-sm line-clamp-3">{{ $doc->description }}</p>
                                <div class="mt-2 flex gap-2">
                                    <a href="{{ asset($doc->file_path) }}" target="_blank"
                                        class="px-3 py-1 bg-orange-600 text-white rounded">Unduh PDF</a>
                                    <button onclick="navigator.clipboard.writeText('{{ url($doc->file_path) }}')"
                                        class="px-3 py-1 border rounded">Salin Tautan</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="h-20"></div>

</body>

</html>
