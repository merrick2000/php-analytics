@section('site_title', formatTitle([$website->url, __('Realtime'), config('settings.title')]))

<div class="card border-0 rounded-top shadow-sm mb-3">
    <div class="px-3 border-bottom">
        <div class="row">
            <!-- Title -->
            <div class="col-12 col-md-auto d-none d-xl-flex align-items-center border-bottom border-md-bottom-0 {{ (__('lang_dir') == 'rtl' ? 'border-md-left' : 'border-md-right') }}">
                <div class="px-2 py-4 d-flex">
                    <div class="d-flex position-relative text-primary width-10 height-10 align-items-center justify-content-center flex-shrink-0">
                        <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-35"></div>
                        @include('icons.live', ['class' => 'fill-current width-5 height-5'])
                    </div>
                </div>
            </div>

            <div class="col-12 col-md">
                <div class="row">
                    <!-- Visitors -->
                    <div class="col-12 col-md-6 border-bottom border-md-bottom-0 {{ (__('lang_dir') == 'rtl' ? 'border-md-left' : 'border-md-right')  }}">
                        <div class="px-2 py-4">
                            <div class="d-flex">
                                <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                    <div class="d-flex align-items-center text-truncate">
                                        <div class="d-flex align-items-center justify-content-center bg-visitor border-radius-25 width-4 height-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"></div>

                                        <div class="flex-grow-1 d-flex font-weight-bold text-truncate">
                                            {{ __('Visitors') }}
                                            <div class="flex-shrink-0 d-flex align-items-center mx-2" data-enable="tooltip" title="{{ __('A visitor represents a page load of your website through direct access, or through a referrer.') }}">
                                                @include('icons.info', ['class' => 'width-4 height-4 fill-current text-muted'])
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center text-truncate" id="visitors-growth">
                                        —
                                    </div>
                                </div>

                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                                    <div class="h2 font-weight-bold mb-0" id="visitors-count">0</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pageviews -->
                    <div class="col-12 col-md-6">
                        <div class="px-2 py-4">
                            <div class="d-flex">
                                <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                    <div class="d-flex align-items-center text-truncate">
                                        <div class="d-flex align-items-center justify-content-center bg-pageview border-radius-25 width-4 height-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"></div>

                                        <div class="flex-grow-1 d-flex font-weight-bold text-truncate">
                                            {{ __('Pageviews') }}
                                            <div class="flex-shrink-0 d-flex align-items-center mx-2" data-enable="tooltip" title="{{ __('A pageview represents a page load of your website.') }}">
                                                @include('icons.info', ['class' => 'width-4 height-4 fill-current text-muted'])
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center text-truncate" id="pageviews-growth">
                                        —
                                    </div>
                                </div>

                                <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                                    <div class="h2 font-weight-bold mb-0" id="pageviews-count">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div style="height: 230px">
            <canvas id="realtime-chart"></canvas>
        </div>
    </div>
</div>

<div class="d-flex flex-column">
    <div class="card border-0 shadow-sm mt-3 h-100 overflow-hidden">
        <div class="card-header align-items-center">
            <div class="row">
                <div class="col"><div class="font-weight-medium py-1">{{ __('Realtime activity') }}</div></div>
                <div class="col-auto"><button type="button" class="btn btn-sm btn-outline-primary" id="pause-button">{{ __('Pause') }}</button></div>
            </div>
        </div>

        <div class="card-body" id="recent">
            {{ __('Loading') }}...
        </div>
    </div>
</div>

<script>
    'use strict';

    let pauseButton = document.querySelector('#pause-button');

    document.addEventListener("DOMContentLoaded", function() {
        Chart.defaults.global.defaultFontFamily = "Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'";

        const ctx = document.querySelector('#realtime-chart').getContext('2d');

        const uniqueColor = '#007bff';
        const pageViewsColor = '#dc3545';

        let range = (start, end) => {
            let foo = [];
            for (var i = end; i >= start; i--) {
                if (i === 0) {
                    foo.push('' + i + ' {{ mb_substr(mb_strtolower(__('Seconds')), 0, 3) }}');
                } else {
                    foo.push('-' + i + ' {{ mb_substr(mb_strtolower(__('Seconds')), 0, 3) }}');
                }
            }
            return foo;
        };

        let tooltipTitles = [
            ...range(0, 60)
        ];

        let realtimeChart = new Chart(ctx, {
            type: 'bar',

            data: {
                labels: [
                    ...range(0, 60)
                ],
                datasets: [{
                    label: '{{ __('Visitors') }}',
                    data: [],
                    backgroundColor : uniqueColor,
                    borderColor: uniqueColor,
                    xAxisID: "bar-x-axis1",
                }, {
                    label: '{{ __('Pageviews') }}',
                    data: [],
                    backgroundColor : pageViewsColor,
                    borderColor: pageViewsColor,
                    xAxisID: "bar-x-axis2",
                }]
            },
            options: {
                animation: false,
                legend: {
                    rtl: {{ (__('lang_dir') == 'rtl' ? 'true' : 'false') }},
                    display: false,
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'round',
                    }
                },
                tooltips: {
                    rtl: {{ (__('lang_dir') == 'rtl' ? 'true' : 'false') }},
                    mode: 'index',
                    intersect: false,
                    reverse: true,
                    backgroundColor: '{{ (request()->cookie('dark_mode') == 1 ? '#FFF' : '#000') }}',

                    xPadding: 16,
                    yPadding: 16,

                    titleFontColor: '{{ (request()->cookie('dark_mode') == 1 ? '#000' : '#FFF') }}',
                    titleSpacing: 30,
                    titleFontSize: 16,
                    titleFontStyle: 'normal',
                    titleMarginBottom: 10,

                    bodyFontColor: '{{ (request()->cookie('dark_mode') == 1 ? '#000' : '#FFF') }}',
                    bodyFontSize: 14,
                    bodySpacing: 10,

                    cornerRadius: 4,
                    caretSize: 7,

                    callbacks: {
                        label: function (tooltipItem, data) {
                            return ' ' + data.datasets[tooltipItem.datasetIndex].label + ': ' + parseFloat(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]).format(0, 3, '{{ __(',') }}').toString();
                        },
                        title: function (tooltipItem) {
                            return tooltipTitles[tooltipItem[0].index];
                        }
                    }
                },
                hover: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    xAxes: [{
                        display: false,
                        stacked: true,
                        id: "bar-x-axis1",
                    }, {
                        display: true,
                        offset: true,
                        stacked: true,
                        id: "bar-x-axis2",
                        gridLines: {
                            lineWidth: 0,
                            zeroLineWidth: 1,
                            tickMarkLength: 0,
                            offsetGridLines: true,
                        },
                        ticks: {
                            maxTicksLimit: 12,
                            padding: 10,
                        }
                    }],
                    yAxes: [{
                        stacked: false,
                        gridLines: {
                            tickMarkLength: 0
                        },
                        display: true,
                        ticks: {
                            beginAtZero: true,
                            maxTicksLimit: 8,
                            padding: 10,
                            callback: function(value) {
                                return commarize(value, 1000);
                            }
                        }
                    }],
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });

        let realTime = () => {
            // Cancel future requests
            if(pauseButton.classList.contains('active')) {
                return;
            }

            // This promise will resolve when the network call succeeds
            // Feel free to make a REST fetch using promises and assign it to networkPromise
            let requestPromise = fetch('{{ request()->url() }}', {
                    headers: {
                        "Accept" : "application/json, text/javascript; charset=utf-8",
                        "Content-Type": "application/json, text/javascript; charset=utf-8"
                    }
                })
                .then(res => res.json())
                .then(response => {
                    let visitorsCount = 0;
                    let index = 0;
                    for (const count in response.visitors) {
                        let time = count.split(' ');

                        if (isNaN(response.visitors[count]) === false) {
                            // Add the unique users
                            visitorsCount += Number(response.visitors[count]);
                        } else {
                            // Set the chart value
                            visitorsCount += 0;
                        }

                        // Set the chart value
                        realtimeChart.data.datasets[0].data[index] = isNaN(response.visitors[count]) ? 0 : response.visitors[count];
                        tooltipTitles[index] = count;

                        index++;
                    }

                    let pageviewsCount = 0;
                    index = 0;
                    for (const count in response.pageviews) {
                        if (isNaN(response.pageviews[count]) === false) {
                            // Add the unique users
                            pageviewsCount += Number(response.pageviews[count]);
                        } else {
                            // Set the chart value
                            pageviewsCount += 0;
                        }

                        // Set the chart value
                        realtimeChart.data.datasets[1].data[index] = isNaN(response.pageviews[count]) ? 0 : response.pageviews[count];
                        tooltipTitles[index] = count;

                        index++;
                    }

                    realtimeChart.update();

                    visitorsCount = visitorsCount.format(0, 3, '{{ __(',') }}').toString();
                    pageviewsCount = pageviewsCount.format(0, 3, '{{ __(',') }}').toString();

                    let targets = [
                        ['#recent', response.recent],
                        ['#visitors-count', visitorsCount],
                        ['#pageviews-count', pageviewsCount],
                        ['#visitors-growth', response.visitors_growth],
                        ['#pageviews-growth', response.pageviews_growth]
                    ];

                    targets.forEach(function (element) {
                        if (document.querySelector(element[0]).innerHTML !== element[1]) {
                            document.querySelector(element[0]).classList.add('d-none');
                            document.querySelector(element[0]).innerHTML = element[1];
                            document.querySelector(element[0]).classList.remove('d-none');
                        }
                    });
                })
                .catch(err => {
                    console.log(err);
                });

            // This promise will resolve when the delay has ended
            let timeOutPromise = new Promise(function(resolve, reject) {
                // Set the delay
                setTimeout(resolve, 2000);
            });

            // Check if all promises are resolved
            Promise.all([requestPromise, timeOutPromise])
                .then(function(values) {
                    realTime();
                });
        }

        realTime();

        pauseButton.addEventListener('click', function () {
            // Disable the pause state
            if (pauseButton.classList.contains('active')) {

                pauseButton.classList.remove('active');
                pauseButton.textContent = '{{ __('Pause') }}';

                realTime();
            }
            // Enable the pause state
            else {
                pauseButton.classList.add('active');
                pauseButton.textContent = '{{ __('Paused') }}';
            }
        });

        // Update the tooltip color
        document.querySelector('#dark-mode').addEventListener('click', function(e) {
            e.preventDefault();

            realtimeChart.options.tooltips.backgroundColor = (this.dataset.darkMode == 0 ? '#FFF' : '#000');
            realtimeChart.options.tooltips.titleFontColor = (this.dataset.darkMode == 0 ? '#000' : '#FFF');
            realtimeChart.options.tooltips.bodyFontColor = (this.dataset.darkMode == 0 ? '#000' : '#FFF');
            realtimeChart.update();
        });
    });
</script>