<!-- Footer Start -->
<div class="container-fluid footer">
            <div class="container py-5">
                <div class="row g-5">
                    <!-- Kolom pertama: Logo dan paragraf -->
                    <div class="col-md-6 col-lg-6 col-xl-3 d-flex flex-column">
                        <div class="logo-container d-flex justify-content-start mb-4">
                            <img src="{{ asset('img/jdihn.png') }}" alt="logo_jdihn"
                                style="height: 80px; width: 80px; margin-right: 15px;">
                            <img src="{{ asset('img/bnpb.png') }}" alt="logo_bnpb" style="height: 80px; width: 80px;">
                        </div>
                        <div class="content-container mt-auto">
                            <p class="mb-4" style="text-align: justify; color: #E0E0E0;">
                                Jaringan Dokumentasi dan Informasi Hukum Nasional yang selanjutnya disebut JDIHN adalah
                                wadah
                                pendayagunaan bersama atas dokumen hukum secara tertib terpadu, dan berkesinambungan,
                                serta
                                merupakan sarana pemberian pelayanan informasi hukum secara lengkap, akurat, mudah, dan
                                cepat.
                            </p>
                        </div>
                    </div>

                    <!-- Kolom kedua: Alamat -->
                    <div class="col-md-6 col-lg-3 col-xl-3 align-self-center">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="mb-4 text-white">Alamat</h4>
                            <a href=""><i class="fas fa-location-dot me-2"></i> Graha BNPB - Jl. Pramuka Kav.38 Jakarta
                                Timur
                                13120</a>
                            <a href=""><i class="fas fa-phone-alt me-2"></i> 021-29827793</a>
                            <a href=""><i class="fas fa-fax me-2"></i> 021-21281200</a>
                            <a href=""><i class="fas fa-envelope me-2"></i> persuratan@bnpb.go.id</a>
                        </div>
                    </div>

                    <!-- Kolom ketiga: Tautan Terkait -->
                    <div class="col-md-6 col-lg-3 col-xl-3 align-self-center">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="mb-4 text-white">Tautan Terkait</h4>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Website BNPB</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Portal JDIHN</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Portal BPHN</a>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-share fa-2x text-white me-2"></i>
                            <a class="btn-square btn btn-primary rounded-circle mx-1" href=""><i
                                    class="fab fa-facebook-f"></i></a>
                            <a class="btn-square btn btn-primary rounded-circle mx-1" href=""><i
                                    class="fab fa-twitter"></i></a>
                            <a class="btn-square btn btn-primary rounded-circle mx-1" href=""><i
                                    class="fab fa-instagram"></i></a>
                            <a class="btn-square btn btn-primary rounded-circle mx-1" href=""><i
                                    class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>

                    <!-- Kolom keempat: Elemen lainnya -->
                    <div class="col-md-6 col-lg-3 col-xl-3 align-self-center">
                        <div class="footer-item">
                            <div class="elfsight-app-26462770-b2a5-4134-a5cf-e2dc315ff16b" data-elfsight-app-lazy></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <!-- Copyright Start -->
        <div class="container-fluid copyright text-body" style="background-color: #1a1a1a;">
            <div class="container text-center">
                <div class="row g-2 align-items-center mt-0 mb-1">
                    <!-- Kolom 1: Hak Cipta -->
                    <div class="col-12">
                        <p class="mb-0 text-white">
                            <i class="fas fa-copyright me-2"></i>
                            <a class="text-white fw-bold" href="#">Badan Nasional Penanggulangan Bencana</a>, All rights
                            reserved.
                        </p>
                    </div>

                    <!-- Kolom 2: Desain oleh -->
                    <div class="col-12">
                        <p class="mb-0 text-white">
                            Designed By
                            <a class="text-primary fw-bold" href="https://htmlcodex.com" target="_blank">HTML Codex</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copyright End -->



        <!-- Back to Top -->
        <a href="#" class="btn btn-primary btn-primary-outline-0 btn-md-square back-to-top"><i
                class="fa fa-arrow-up"></i></a>


        <!-- JavaScript Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
        <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
        <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('lib/lightbox/js/lightbox.min.js') }}"></script>


        <!-- Template Javascript -->
        <script src="{{ asset('js/main.js') }}"></script>
        <script src="https://static.elfsight.com/platform/platform.js" async></script>

        <script>
            document.getElementById('filterButton').addEventListener('click', function() {
                var filterSection = document.getElementById('filterSection');
                if (filterSection.style.display === 'none' || filterSection.style.display === '') {
                    filterSection.style.display = 'block';
                } else {
                    filterSection.style.display = 'none';
                }
            });
        </script>

        <!-- Import PureCounter Script -->
        <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs/dist/purecounter_vanilla.js"></script>
        <script>
            new PureCounter();
        </script>

        <!-- Script untuk Doughnut Chart -->
        <script>
            const averageRatingCtx = document.getElementById('averageRatingChart').getContext('2d');
            new Chart(averageRatingCtx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [4.9, 5 - 4.9], // Nilai rata-rata dan sisa skala 5
                        backgroundColor: ['#13357B', '#E0E0E0'], // Warna grafik
                        borderWidth: 0 // Tidak ada border
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '75%', // Membuat lingkaran bagian tengah lebih besar
                    plugins: {
                        legend: {
                            display: false
                        } // Menyembunyikan legend default
                    },
                }
            });
        </script>

        <!-- Script untuk Grafik Batang Horizontal -->
        <script>
            const ratingDistributionCtx = document.getElementById('ratingDistributionChart').getContext('2d');
            new Chart(ratingDistributionCtx, {
                type: 'bar',
                data: {
                    labels: ['5', '4', '3', '2', '1'], // Label nilai
                    datasets: [{
                        label: 'Jumlah Penilaian',
                        data: [700, 50, 20, 3, 1], // Data jumlah penilaian untuk masing-masing rating
                        backgroundColor: [
                            '#13357B', // Warna untuk 5
                            '#0056B3', // Warna untuk 4
                            '#007BFF', // Warna untuk 3
                            '#6EB5FF', // Warna untuk 2
                            '#B3D9FF' // Warna untuk 1
                        ],
                        borderRadius: 5, // Membuat sudut batang lebih halus
                        barThickness: 15 // Ketebalan batang
                    }]
                },
                options: {
                    indexAxis: 'y', // Membuat grafik horizontal
                    responsive: true,
                    maintainAspectRatio: false, // Supaya tinggi/ukuran lebih fleksibel
                    plugins: {
                        legend: {
                            display: false // Menyembunyikan legenda
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.raw + ' Penilaian'; // Menampilkan jumlah penilaian pada tooltip
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true, // Mulai dari 0 di sumbu X
                            ticks: {
                                stepSize: 100 // Skala angka pada sumbu X
                            },
                            grid: {
                                drawOnChartArea: false // Menyembunyikan garis grid vertikal
                            }
                        },
                        y: {
                            grid: {
                                drawOnChartArea: false // Menyembunyikan garis grid horizontal
                            }
                        }
                    }
                }
            });
        </script>
</body>

</html>