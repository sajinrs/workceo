<div id="morris-area-chart-invoice" style="height: 190px;"></div>

<script>
   $(document).ready(function () {

        var chartData = {!!  $chartData !!};

        function barChart() {

            Morris.Bar({
                element: 'morris-area-chart-invoice',
                data: chartData,
                xkey: 'date',
                ykeys: ['total'],
                labels: ['Earning'],
                pointSize: 3,
                fillOpacity: 0,
                pointStrokeColors: ['#e20b0b'],
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                lineWidth: 2,
                hideHover: 'auto',
                lineColors: ['#e20b0b'],
                resize: true,
                hoverCallback: function (index, options, content, row) {
                    var hover = "<div class='morris-hover-row-label'>"+row.client+" ("+row.currency_symbol+row.total+")</div>";
                    return hover;
                },
                barColors:['{{$barColor}}']

            });

        }
        if(chartData.length >0){
            barChart();
        }



    });
</script>