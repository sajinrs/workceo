<div class="row">
    <div class="col-sm-12">
        <div class="f-z-10">{!! $pp_rate !!} from prior period</div>
        <div class="f-z-10">{{$ytd}} total YTD</div>
    </div>
    <div class="col-6">

        <h1 class="font-primary counter"   style="{{$style}}">{{$project_count}}</h1>
        <h6 class="mb-0">Jobs</h6>

    </div>
    <div class="col-md-6">
        <div class="jobsChart chart-block chart-vertical-center" style="height: 104px">
            <canvas id="myDoughnutGraph"></canvas>
        </div>
    </div>
</div>

<script>
     var doughnutData = {!!  $graph_data !!};

        var doughnutOptions = {
            segmentShowStroke: true,
            segmentStrokeColor: "#fff",
            segmentStrokeWidth: 2,
            percentageInnerCutout: 50,
            animationSteps: 100,
            animationEasing: "easeOutBounce",
            animateRotate: true,
            animateScale: false

        };
        if(doughnutData.length >0){
            var doughnutCtx = document.getElementById("myDoughnutGraph").getContext("2d");
            var myDoughnutChart = new Chart(doughnutCtx).Doughnut(doughnutData, doughnutOptions);
        }
   </script>