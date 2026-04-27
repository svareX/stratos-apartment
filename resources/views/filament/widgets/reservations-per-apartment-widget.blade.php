<?php $chartId = 'chart-'.uniqid(); ?>
<div class="filament-widget p-4" style="background:#1f1f23;border-radius:12px;color:#e6e6e6;">
    <div style="font-weight:700;color:#f59e0b;margin-bottom:0.4rem">Top Apartments</div>
    <div id="{{ $chartId }}" style="height:260px"></div>

    <script>
        (function(){
            function render(){
                var labels = {!! json_encode(array_map(fn($r)=>$r['apartment'],$top)) !!};
                var data = {!! json_encode(array_map(fn($r)=>$r['total'],$top)) !!};

                var options = {
                    chart: { type: 'bar', height: 260 },
                    series: [{ name: 'Reservations', data: data }],
                    colors: ['#f59e0b'],
                    xaxis: { categories: labels, labels: { style: { colors: '#cbd5e1' } } },
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
