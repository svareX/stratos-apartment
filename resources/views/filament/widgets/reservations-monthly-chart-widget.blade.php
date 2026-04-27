<?php $chartId = 'chart-'.uniqid(); ?>
<div class="filament-widget p-4" style="background:#1f1f23;border-radius:12px;color:#e6e6e6;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
        <div style="font-weight:700;color:#f59e0b">Reservations / Revenue — {{ now()->year }}</div>
    </div>
    <div id="{{ $chartId }}" style="height:260px"></div>

    <script>
        (function(){
            function render(){
                var labels = {!! json_encode($labels) !!};
                var counts = {!! json_encode(array_map(fn($d)=>$d['count'],$data)) !!};
                var revenue = {!! json_encode(array_map(fn($d)=>$d['revenue'],$data)) !!};

                var options = {
                    chart: { type: 'bar', height: 260, toolbar: { show: false } },
                    colors: ['#f59e0b','#fb923c'],
                    series: [
                        { name: 'Reservations', data: counts },
                        { name: 'Revenue', data: revenue }
                    ],
                    xaxis: { categories: labels, labels: { style: { colors: '#cbd5e1' } } },
                    yaxis: [{ title: { text: 'Count', style: { color: '#cbd5e1' } } }, { opposite: true, title: { text: 'Revenue', style: { color: '#cbd5e1' } } }],
                    tooltip: { theme: 'dark' },
                    legend: { labels: { colors: '#cbd5e1' } },
                    plotOptions: { bar: { columnWidth: '50%' } },
                    dataLabels: { enabled: false },
                    theme: { mode: 'dark' }
                };

                var el = document.getElementById('{{ $chartId }}');
                if (!el) return;
                if (typeof ApexCharts === 'undefined') {
                    var s = document.createElement('script');
                    s.src = 'https://cdn.jsdelivr.net/npm/apexcharts';
                    s.onload = function(){ new ApexCharts(el, options).render(); };
                    document.head.appendChild(s);
                } else {
                    new ApexCharts(el, options).render();
                }
            }

            if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', render); else render();
        })();
    </script>
</div>
