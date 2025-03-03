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
                            <a href="https://www.facebook.com/infobnpb"><i class="fab fa-facebook-f facebook-bg"></i></a>
                            <a href="https://twitter.com/BNPB_Indonesia"><i class="fab fa-twitter twitter-bg"></i></a>
                            <a href="https://www.instagram.com/bnpb_indonesia/"><i class="fab fa-instagram instagram-bg"></i></a>
                            <a href="https://www.youtube.com/user/BNPBIndonesia/"><i class="fab fa-youtube youtube-bg"></i></a>
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
                        <div class="footer-widget-heading pt-4">
                            <h3>Subscribe</h3>
                        </div>
                        <div class="subscribe-form">
                            <form action="#">
                                <input type="text" placeholder="Email Address">
                                <button><i class="fab fa-telegram-plane"></i></button>
                            </form>
                        </div>
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

<!-- Livewire Scripts -->
@livewireScripts
</body>

<script>
    window.addEventListener('show-lampiran-modal', event => {
        var myModal = new bootstrap.Modal(document.getElementById('mediaFile'));
        myModal.show();
    });
</script>

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

</body>

</html>