import './bootstrap';
import '../css/app.css';

import 'bootstrap';

document.addEventListener('DOMContentLoaded', () => {

    // ===================================
    // SCROLL REVEAL
    // ===================================

    const revealElements = document.querySelectorAll('[data-reveal]');

    if ('IntersectionObserver' in window) {

        document.documentElement.classList.add('js-reveal');

        const observer = new IntersectionObserver((entries, currentObserver) => {

            entries.forEach(entry => {

                if (entry.isIntersecting) {

                    entry.target.classList.add('is-visible');

                    currentObserver.unobserve(entry.target);

                }

            });

        }, {
            threshold: 0.12
        });

        revealElements.forEach(element => observer.observe(element));

    } else {

        revealElements.forEach(element => {
            element.classList.add('is-visible');
        });

    }


    // ===================================
    // PERFORMANCE CHARTS
    // ===================================

    const performanceData = window.temanDengarPerformance;

    if (!performanceData) {
        return;
    }

    import('chart.js/auto')
        .then(({ default: Chart }) => {

            // MONTHLY TREND

            const monthlyCanvas = document.getElementById(
                'monthlyConsultationChart'
            );

            if (monthlyCanvas) {

                new Chart(monthlyCanvas, {

                    type: 'line',

                    data: {
                        labels: performanceData.monthly.labels,

                        datasets: [

                            {
                                label: 'Permintaan Baru',
                                data: performanceData.monthly.requests,
                                borderColor: '#6259D9',
                                backgroundColor: 'rgba(98, 89, 217, 0.10)',
                                fill: true,
                                tension: 0.35,
                                borderWidth: 3,
                                pointRadius: 4,
                            },

                            {
                                label: 'Sesi Selesai',
                                data: performanceData.monthly.completed,
                                borderColor: '#64A995',
                                backgroundColor: 'rgba(100, 169, 149, 0.10)',
                                fill: true,
                                tension: 0.35,
                                borderWidth: 3,
                                pointRadius: 4,
                            }

                        ]
                    },

                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },

                        plugins: {

                            legend: {
                                position: 'bottom',
                            },

                        },

                        scales: {

                            y: {
                                beginAtZero: true,

                                ticks: {
                                    precision: 0,
                                },

                                grid: {
                                    color: '#F0EFF5',
                                },

                            },

                            x: {
                                grid: {
                                    display: false,
                                },
                            },

                        },

                    }

                });

            }


            // TOPIC DISTRIBUTION

            const topicCanvas = document.getElementById('topicChart');

            if (topicCanvas) {

                new Chart(topicCanvas, {

                    type: 'doughnut',

                    data: {

                        labels: performanceData.topics.labels,

                        datasets: [{

                            data: performanceData.topics.values,

                            backgroundColor: [
                                '#6259D9',
                                '#64A995',
                                '#F0AD72',
                                '#74A6D5',
                                '#B7A6D9',
                            ],

                            borderWidth: 0,

                            hoverOffset: 8,

                        }]

                    },

                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        cutout: '65%',

                        plugins: {

                            legend: {
                                position: 'bottom',
                            }

                        }

                    }

                });

            }


            // STATUS DISTRIBUTION

            const statusCanvas = document.getElementById('statusChart');

            if (statusCanvas) {

                new Chart(statusCanvas, {

                    type: 'doughnut',

                    data: {

                        labels: performanceData.statuses.labels,

                        datasets: [{

                            data: performanceData.statuses.values,

                            backgroundColor: [
                                '#F0AD35',
                                '#3F9BD8',
                                '#6259D9',
                                '#329D79',
                                '#E16A74',
                                '#9A8DB5',
                                '#74788B',
                            ],

                            borderWidth: 0,

                            hoverOffset: 8,

                        }]

                    },

                    options: {

                        responsive: true,

                        maintainAspectRatio: false,

                        cutout: '65%',

                        plugins: {

                            legend: {
                                position: 'bottom',
                            }

                        }

                    }

                });

            }

        })

        .catch(error => {
            console.error('Gagal memuat Chart.js:', error);
        });

});