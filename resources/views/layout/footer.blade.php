<!-- Footer Start -->
<footer class="footer-section">
    <div class="container">
        <div class="footer-content pt-3 pb-3">
            <div class="row">
                <!-- Kolom 1 -->
                <div class="col-xl-4 col-lg-4 mb-50">
                    <div class="footer-widget">
                        <div class="footer-logo">
                            <a href="/"><img src="{{ asset('img/update_logo.png') }}" class="img-fluid" alt="logo"></a>
                        </div>
                        <div class="footer-text me-5">
                            <p>Jaringan Dokumentasi dan Informasi Hukum Nasional yang selanjutnya disebut JDIHN adalah wadah pendayagunaan bersama atas dokumen hukum secara tertib terpadu, dan berkesinambungan, serta merupakan sarana pemberian pelayanan informasi hukum secara lengkap, akurat, mudah, dan cepat.</p>
                        </div>
                        <div class="footer-social-icon">
                            <span>Follow us</span>
                            <a href="https://www.facebook.com/infobnpb" target="_blank"><i class="fab fa-facebook-f facebook-bg"></i></a>
                            <a href="https://twitter.com/BNPB_Indonesia" target="_blank"><i class="fab fa-twitter twitter-bg"></i></a>
                            <a href="https://www.instagram.com/bnpb_indonesia/" target="_blank"><i class="fab fa-instagram instagram-bg"></i></a>
                            <a href="https://www.youtube.com/user/BNPBIndonesia/" target="_blank"><i class="fab fa-youtube youtube-bg"></i></a>
                        </div>
                    </div>
                </div>
                <!-- Kolom 2 -->
                <div class="col-xl-2 col-lg-2 col-md-6 mb-30">
                    <div class="footer-widget pt-5">
                        <div class="footer-widget-heading">
                            <h3>JDIH BNPB</h3>
                        </div>
                        <ul class="list-unstyled">
                            <li><a href="#">Profil</a></li>
                            <li><a href="#">Struktur Organisasi</a></li>
                            <li><a href="#">Berita</a></li>
                            <li><a href="#">Statistik</a></li>
                            <li><a href="#">Visi dan Misi</a></li>
                        </ul>
                    </div>
                </div>
                <!-- Kolom 3 -->
                <div class="col-xl-2 col-lg-2 col-md-6 mb-30">
                    <div class="footer-widget pt-5">
                        <div class="footer-widget-heading">
                            <h3>TAUTAN</h3>
                        </div>
                        <ul class="list-unstyled">
                            <li><a href="https://bnpb.go.id">Website BNPB</a></li>
                            <li><a href="https://jdihn.go.id/">Portal JDIH Nasional</a></li>
                            <li><a href="http://bphn.jdihn.go.id/">Portal BPHN</a></li>
                        </ul>
                    </div>
                </div>
                <!-- Kolom 4 -->
                <div class="col-xl-4 col-lg-4 col-md-6 mb-50">
                    <div class="footer-widget pt-5">
                        <div class="footer-widget-heading">
                            <h3>HUBUNGI KAMI</h3>
                        </div>
                        <div class="footer-text mb-25">
                            <ul style="color: dimgrey;">
                                <li><i class="fas fa-home me-2"></i>Graha BNPB - Jl. Pramuka Kav.38 Jakarta Timur 13120</li>
                                <li><i class="fas fa-phone-alt me-2"></i> 021-29827793</li>
                                <li><i class="fas fa-fax me-2"></i> 021-21281200</li>
                                <li><i class="fas fa-envelope me-2"></i> jdih@bnpb.go.id</li>
                            </ul>
                        </div>
                        <!-- <div class="footer-widget-heading pt-4">
                            <h3>Subscribe</h3>
                        </div>
                        <div class="subscribe-form">
                            <form action="#">
                                <input type="text" placeholder="Email Address">
                                <button><i class="fab fa-telegram-plane"></i></button>
                            </form>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 text-center">
                    <div class="copyright-text">
                        <p>Copyright &copy; 2024, All Right Reserved <a href="https://bnpb.go.id/">Badan Nasional Penanggulangan Bencana</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Copyright End -->
 
<!-- Back to Top -->
<a href="#" class="btn btn-primary btn-primary-outline-0 btn-md-square back-to-top"><i
        class="fa fa-arrow-up"></i></a>
</body>

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
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>

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
    document.addEventListener('DOMContentLoaded', function() {
        // Elemen Canvas
        const averageRatingCtx = document.getElementById('averageRatingChart').getContext('2d');

        // Fetch data dari API
        fetch('/api/rating-average')
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                const averageRating = data.average; // Rata-rata dari API
                const remaining = 5 - averageRating; // Sisa dari skala 5

                // Inisialisasi Chart.js
                new Chart(averageRatingCtx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [averageRating, remaining], // Data dinamis
                            backgroundColor: ['#13357B', '#E0E0E0'], // Warna grafik
                            borderWidth: 0 // Tidak ada border
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '75%', // Membuat lingkaran bagian tengah lebih besar
                        plugins: {
                            legend: {
                                display: false // Menyembunyikan legend
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        // Menampilkan nilai rata-rata atau sisa
                                        return context.raw === averageRating ?
                                            `Rata-rata: ${averageRating}` :
                                            `Sisa: ${remaining.toFixed(1)}`;
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching average rating:', error);
            });
    });
</script>

<!-- Script untuk Grafik Batang Horizontal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch data dari API
        fetch('/api/rating')
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(ratingData => {
                const ratingLabels = [
                    '★★★★★', // Label untuk rating 5
                    '★★★★', // Label untuk rating 4
                    '★★★', // Label untuk rating 3
                    '★★', // Label untuk rating 2
                    '★' // Label untuk rating 1
                ];

                // Membuat grafik Chart.js
                const ratingDistributionCtx = document.getElementById('ratingDistributionChart').getContext('2d');
                new Chart(ratingDistributionCtx, {
                    type: 'bar',
                    data: {
                        labels: ratingLabels, // Label berupa bintang
                        datasets: [{
                            label: 'Jumlah Penilaian',
                            data: ratingData, // Data dari API
                            backgroundColor: [
                                '#13357B', // Warna untuk 5
                                '#0056B3', // Warna untuk 4
                                '#007BFF', // Warna untuk 3
                                '#6EB5FF', // Warna untuk 2
                                '#B3D9FF' // Warna untuk 1
                            ],
                            borderRadius: 1,
                            barThickness: 25
                        }]
                    },
                    options: {
                        indexAxis: 'y', // Membuat grafik horizontal
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false // Menyembunyikan legenda
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.raw + ' Penilaian'; // Tampilkan jumlah penilaian di tooltip
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1 // Skala angka pada sumbu X
                                },
                                grid: {
                                    drawOnChartArea: false
                                }
                            },
                            y: {
                                grid: {
                                    drawOnChartArea: false
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching rating distribution:', error);
            });
    });
</script>

<!-- Fetch API summary rating -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch data dari API
        fetch('/api/rating-summary')
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                const averageRating = data.average;
                const totalRatings = data.total;

                // Update nilai rata-rata dan jumlah penilaian
                document.getElementById('averageRating').textContent = averageRating;
                document.getElementById('totalRatings').textContent = `${totalRatings} Penilaian`;

                // Update bintang
                const starContainer = document.getElementById('starRating');
                starContainer.innerHTML = ''; // Kosongkan kontainer bintang sebelumnya

                // Menampilkan bintang penuh, setengah, dan kosong
                const fullStars = Math.floor(averageRating); // Bintang penuh
                const halfStar = averageRating % 1 >= 0.5 ? 1 : 0; // Bintang setengah
                const emptyStars = 5 - fullStars - halfStar; // Bintang kosong

                // Tambahkan bintang penuh
                for (let i = 0; i < fullStars; i++) {
                    starContainer.innerHTML += '<span>&#9733;</span>'; // Bintang penuh
                }

                // Tambahkan bintang setengah (jika ada)
                if (halfStar) {
                    starContainer.innerHTML += '<span>&#9734;</span>'; // Bintang setengah
                }

                // Tambahkan bintang kosong
                for (let i = 0; i < emptyStars; i++) {
                    starContainer.innerHTML += '<span>&#9734;</span>'; // Bintang kosong
                }
            })
            .catch(error => {
                console.error('Error fetching rating summary:', error);
            });
    });
</script>

<!-- Script untuk Autoslider -->
<script>
    $(document).ready(function() {
        $(".owl-carousel").owlCarousel({
            loop: true, // Untuk looping infinite
            margin: 10, // Jarak antar card
            nav: false, // Tombol navigasi
            dots: true, // Tampilkan pagination dots
            autoplay: true, // Aktifkan autoplay
            autoplayTimeout: 3500, // Kecepatan 5 detik
            autoplayHoverPause: true, // Pause saat hover
            slideTransition: 'ease-in-out', // Transisi yang halus
            responsive: {
                0: {
                    items: 1 // Tampilan 1 card di layar kecil
                },
                768: {
                    items: 2 // Tampilan 3 card di layar medium
                },
                1200: {
                    items: 4 // Tampilan maksimal 6 card di layar besar
                }
            }
        });
    });
</script>

<!-- Swiper Initialization -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 4,
            spaceBetween: 20,
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                    spaceBetween: 10
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 15
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 20
                },
                1200: {
                    slidesPerView: 4,
                    spaceBetween: 20
                },
            }
        });
    });
</script>

<!-- Survey Penilaian Website -->
<script>
    // Select all stars
    const stars = document.querySelectorAll('.star');
    const rateInput = document.getElementById('rateInput');

    // Handle star click (final selection)
    stars.forEach(star => {
        star.addEventListener('click', () => {
            const value = star.getAttribute('data-value');

            // Set selected stars based on clicked value
            stars.forEach(s => {
                s.classList.remove('selected');
                if (s.getAttribute('data-value') <= value) {
                    s.classList.add('selected');
                }
            });

            // Set the value of hidden input
            rateInput.value = value;
        });

        // Handle hover effect (highlight the stars)
        star.addEventListener('mouseover', () => {
            const value = star.getAttribute('data-value');

            stars.forEach(s => {
                s.classList.remove('hover');
                if (s.getAttribute('data-value') <= value) {
                    s.classList.add('hover');
                }
            });
        });

        // Reset hover effect when mouse leaves the stars
        star.addEventListener('mouseleave', () => {
            stars.forEach(s => {
                s.classList.remove('hover');
            });
        });
    });
</script>

<!-- Sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function submitSurveyForm(event) {
        event.preventDefault(); // Mencegah form submit secara default

        const form = document.getElementById('surveyForm');
        const formData = new FormData(form);

        fetch(form.action, {
                method: form.method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Tampilkan SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Redirect setelah pengguna menutup SweetAlert
                        window.location.href = data.redirect_url;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan, silakan coba lagi.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan pada server.'
                });
            });
    }
</script>


<!-- AOS Library -->
<script>
    AOS.init({
        // ... your other initialisation options here ...
        once: true,
    });
</script>

</body>

</html>