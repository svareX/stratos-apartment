<?php $chartId = 'chart-'.uniqid(); ?>
<div class="filament-widget p-4" style="background:#1f1f23;border-radius:12px;color:#e6e6e6;">
    <div style="font-weight:700;color:#f59e0b;margin-bottom:0.4rem">Reservation Status</div>

    <div id="{{ $chartId }}" style="height:220px"></div>

    <script>
        (function(){
            function render(){
                var groups = {!! json_encode(array_values($groups)) !!};
                var labels = {!! json_encode(array_map('strval', array_keys($groups))) !!};
                var colors = ['#f59e0b','#fb923c','#ef4444','#10b981','#6366f1'];

                var options = {
                    chart: { type: 'donut', height: 220 },
                    series: groups,
                    labels: labels,
                    colors: colors,
                    legend: { labels: { colors: '#cbd5e1' } },
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
