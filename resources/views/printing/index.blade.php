<x-layout.default>
     <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .print-card {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .print-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        
        .print-image {
            height: 180px;
            background-size: cover;
            background-position: center;
        }
        
        .btn-select {
            transition: all 0.2s ease;
        }
        
        .btn-select:hover {
            transform: scale(1.05);
        }
    </style>
    
    <div class="container mx-auto px-4" x-data="{ selected: '' }">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Layanan Percetakan Kami</h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">Pilih jenis layanan percetakan yang Anda butuhkan. Kami menyediakan berbagai solusi cetak dengan kualitas terbaik.</p>
        </div>

        <!-- Printing Options Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Digital Printing Card -->
            <div class="print-card bg-white">
                <div class="print-image bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-print text-blue-500 text-5xl"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Print Digital</h3>
                    <p class="text-gray-600 mb-4">Cetak digital berkualitas tinggi dengan waktu pengerjaan cepat dan harga kompetitif.</p>
                    <a href="/printing/create?type=digital" class="btn-select block w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded-lg font-medium">
                        Pilih Layanan
                    </a>
                </div>
            </div>

            <!-- Screen Printing Card -->
            <div class="print-card bg-white">
                <div class="print-image bg-green-100 flex items-center justify-center">
                    <i class="fas fa-layer-group text-green-500 text-5xl"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Sablon</h3>
                    <p class="text-gray-600 mb-4">Cetak saring untuk media yang beragam dengan hasil yang tahan lama dan warna yang tajam.</p>
                    <a href="/printing/create?type=sablon" class="btn-select block w-full bg-green-500 hover:bg-green-600 text-white text-center py-2 rounded-lg font-medium">
                        Pilih Layanan
                    </a>
                </div>
            </div>

            <!-- Offset Printing Card -->
            <div class="print-card bg-white">
                <div class="print-image bg-red-100 flex items-center justify-center">
                    <i classfas fa-copy text-red-500 text-5xl"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Print Offset</h3>
                    <p class="text-gray-600 mb-4">Cetak offset untuk kebutuhan cetak dalam jumlah besar dengan kualitas tinggi dan konsisten.</p>
                    <a href="/printing/create?type=offset" class="btn-select block w-full bg-red-500 hover:bg-red-600 text-white text-center py-2 rounded-lg font-medium">
                        Pilih Layanan
                    </a>
                </div>
            </div>

            <!-- Sublimation Printing Card -->
            <div class="print-card bg-white">
                <div class="print-image bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-tshirt text-purple-500 text-5xl"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Print Sublimasi</h3>
                    <p class="text-gray-600 mb-4">Cetak sublimasi untuk material khusus seperti kain dan keramik dengan hasil yang awet.</p>
                    <a href="/printing/create?type=sublimasi" class="btn-select block w-full bg-purple-500 hover:bg-purple-600 text-white text-center py-2 rounded-lg font-medium">
                        Pilih Layanan
                    </a>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="mt-16 bg-white rounded-xl shadow-md overflow-hidden">
            <div class="md:flex">
                <div class="md:flex-shrink-0">
                    <div class="h-48 w-full md:w-64 bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-info-circle text-blue-500 text-5xl"></i>
                    </div>
                </div>
                <div class="p-8">
                    <div class="uppercase tracking-wide text-sm text-blue-500 font-semibold">Tentang Layanan Kami</div>
                    <p class="mt-2 text-gray-600">Setiap jenis percetakan memiliki keunggulan tersendiri. Silakan pilih layanan yang paling sesuai dengan kebutuhan Anda. Tim kami akan dengan senang hati membantu merealisasikan project printing Anda.</p>
                    <div class="mt-4">
                        <a href="/order" class="text-blue-500 hover:text-blue-600 font-medium flex items-center">
                            Lihat pesanan saya
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('printing', () => ({
                currentType: '',
                
                selectType(type) {
                    this.currentType = type;
                    // Redirect to create page with selected type
                    window.location.href = `/create?type=${type}`;
                }
            }));
        });
    </script>
</x-layout.default>