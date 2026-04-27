<?php $chartId = 'chart-'.uniqid(); ?>
<div class="filament-widget p-4" style="background:#1f1f23;border-radius:12px;color:#e6e6e6;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
        <div style="font-weight:700;color:#f59e0b">Reservations — Last 30 days</div>
    </div>
    <div id="{{ $chartId }}" style="height:200px"></div>

    <script>
        (function(){
            function render(){
                var labels = {!! json_encode($labels) !!};
                var data = {!! json_encode($data) !!};

                var options = {
                    chart: { type: 'line', height: 200, zoom: { enabled: false } },
                    series: [{ name: 'Reservations', data: data }],
                    stroke: { curve: 'smooth', width: 3 },
                    colors: ['#f59e0b'],
                    xaxis: { categories: labels, labels: { rotate: -45, style: { colors: '#cbd5e1' } } },
                    tooltip: { theme: 'dark' },
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
