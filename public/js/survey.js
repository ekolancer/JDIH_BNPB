(function () {
    function initRatingSummary() {
        const averageRatingCanvas = document.getElementById('averageRatingChart');
        const ratingDistributionCanvas = document.getElementById('ratingDistributionChart');
        const averageRatingElement = document.getElementById('averageRating');
        const totalRatingsElement = document.getElementById('totalRatings');
        const starContainer = document.getElementById('starRating');

        if (!averageRatingCanvas || !ratingDistributionCanvas || !averageRatingElement || !totalRatingsElement || !starContainer || typeof Chart === 'undefined') {
            return;
        }

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
                const ratingData = data.distribution;
                const remaining = Math.max(5 - averageRating, 0);

                new Chart(averageRatingCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [averageRating, remaining],
                            backgroundColor: ['#13357B', '#E0E0E0'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '75%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return context.raw === averageRating ?
                                            `Rata-rata: ${averageRating}` :
                                            `Sisa: ${remaining.toFixed(1)}`;
                                    }
                                }
                            }
                        }
                    }
                });

                new Chart(ratingDistributionCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['★★★★★', '★★★★', '★★★', '★★', '★'],
                        datasets: [{
                            label: 'Jumlah Penilaian',
                            data: ratingData,
                            backgroundColor: [
                                '#13357B',
                                '#0056B3',
                                '#007BFF',
                                '#6EB5FF',
                                '#B3D9FF'
                            ],
                            borderRadius: 1,
                            barThickness: 25
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return context.raw + ' Penilaian';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
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

                averageRatingElement.textContent = averageRating;
                totalRatingsElement.textContent = `${totalRatings} Penilaian`;
                starContainer.innerHTML = '';

                const fullStars = Math.floor(averageRating);
                const halfStar = averageRating % 1 >= 0.5 ? 1 : 0;
                const emptyStars = 5 - fullStars - halfStar;

                for (let i = 0; i < fullStars; i++) {
                    starContainer.innerHTML += '<span>&#9733;</span>';
                }

                if (halfStar) {
                    starContainer.innerHTML += '<span>&#9734;</span>';
                }

                for (let i = 0; i < emptyStars; i++) {
                    starContainer.innerHTML += '<span>&#9734;</span>';
                }
            })
            .catch(error => {
                console.error('Error fetching rating summary:', error);
            });
    }

    function initRatingStars() {
        const stars = document.querySelectorAll('.star');
        const rateInput = document.getElementById('rateInput');

        if (!stars.length || !rateInput) {
            return;
        }

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const value = star.getAttribute('data-value');

                stars.forEach(item => {
                    item.classList.remove('selected');
                    if (item.getAttribute('data-value') <= value) {
                        item.classList.add('selected');
                    }
                });

                rateInput.value = value;
            });

            star.addEventListener('mouseover', () => {
                const value = star.getAttribute('data-value');

                stars.forEach(item => {
                    item.classList.remove('hover');
                    if (item.getAttribute('data-value') <= value) {
                        item.classList.add('hover');
                    }
                });
            });

            star.addEventListener('mouseleave', () => {
                stars.forEach(item => item.classList.remove('hover'));
            });
        });
    }

    window.submitSurveyForm = function (event) {
        event.preventDefault();

        const form = document.getElementById('surveyForm');
        const token = document.querySelector('input[name="_token"]');

        if (!form || !token) {
            return;
        }

        const formData = new FormData(form);

        fetch(form.action, {
            method: form.method,
            headers: {
                'X-CSRF-TOKEN': token.value,
                'Accept': 'application/json'
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
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
    };

    document.addEventListener('DOMContentLoaded', function () {
        initRatingSummary();
        initRatingStars();
    });
})();