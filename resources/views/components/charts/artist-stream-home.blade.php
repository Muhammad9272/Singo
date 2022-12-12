@props(['userId' => null])

<div>
    
    <div class="d-block c-streams-date">
        <div class="d-flex">
           {{--  <a href="" class="badge btn-yellow1 " >All</a> --}}
           <a href="" class="badge btn-yellow1 changealbumdate " data-date="{{App\Helpers\AppHelper::alltime()}}">All Time</a>
            <a href="" class="badge btn-yellow1 changealbumdate active" data-date="{{Carbon\Carbon::now()->subDays(7)->format('Y-m-d')}} - {{Carbon\Carbon::now()->format('Y-m-d')}}">Last 7 days</a>
            <a href="" class="badge btn-yellow1 changealbumdate" data-date="{{Carbon\Carbon::now()->subDays(15)->format('Y-m-d')}} - {{Carbon\Carbon::now()->format('Y-m-d')}}">Last 15 days</a>
            <a href="" class="badge btn-yellow1 changealbumdate" data-date="{{Carbon\Carbon::now()->subDays(30)->format('Y-m-d')}} - {{Carbon\Carbon::now()->format('Y-m-d')}}">Last 30 days</a>
        </div>
    </div>
    
   {{--  <div class="d-flex justify-content-between">
        <div class="">
            <h5 class="text-white" style="font-size:16px"> Streams</h5>
            <h3 id="stream-count">0</h3>
        </div>
        <div class="d-flex c-streams">
            <a href="" class="changealbum active" data-value="0">
                <img  src="{{ asset('image/icons/c_icon1.png') }}">
                <p>All</p>
            </a>
            <a href=""  class="changealbum " data-value="746109">
                <img src="{{ asset('image/icons/c_icon2.png') }}">
                <p>Spotify</p>
            </a>
            <a href="javascript:;" class="changealbum" data-value="1330598">
                <img src="{{ asset('image/icons/c_icon3.png') }}">
                <p>Apple</p>
            </a>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-md-6">
            <div class="">
                <h5 class="text-white" style="font-size:16px"> Streams</h5>
                <h3 id="stream-count">0</h3>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <h5 class="text-white" style="font-size:16px"> Select DSP</h5>
               {{--  <label for="" class="text-white">Select DSP</label> --}}
                <x-form.select2-dsp name="streams-dsp" id="streams-dsp" onchange="updateStreamChart(this)"/>
            </div>
        </div>
    </div>

    <div id="artist_stream_chart" style="height: 300px;"></div>
</div>

@push('page_scripts')
    <script>
        let streamsChartApiUrl = `@chart('artist_streams_chart')`;
        Chart.defaults.global.defaultFontColor='white';
        

        var nf = Intl.NumberFormat();
        let streamChart = new Chartisan({
            el: '#artist_stream_chart',
            url: `${streamsChartApiUrl}?user_id={{ $userId }}`,
            hooks: new ChartisanHooks()
                
                .colors()
                .legend({ display: false })
                .responsive(true)
                
                // .preloader({disabled:true})
                .datasets([
                    {
                        type: 'line',
                        fill: true,
                        tension: 0.3,
                        borderColor: '#FFCC00',
                        backgroundColor:'rgba(255, 255, 0, 0.2)',
                      
                    },
                    
                    {
                        type: 'line',
                        fill: true,
                        tension: 0.3,
                        borderColor: 'blue',
                        backgroundColor:'rgba(0, 0, 255, 0.2)',
                    },
                    {
                        type: 'line',
                        fill: true,
                        tension: 0.3,
                        borderColor: 'red',
                        backgroundColor:'rgba(255, 0, 0, 0.2)',
                    },
                    {
                        type: 'line',
                        fill: true,
                        tension: 0.3,
                        borderColor: 'grey',
                        backgroundColor:'rgba(128,128,128, 0.2)',
                    },
                    {
                        type: 'line',
                        fill: true,
                        tension: 0.3,
                        borderColor: 'green',
                        backgroundColor:'rgba(0, 128, 0, 0.2)',
                    },
                ]).custom(({ data, merge, server }) => {
                    var totalCount = server.datasets[server.datasets.length-1].values[0];
                    totalCount = nf.format(totalCount)
                    $("#stream-count").text(totalCount)
                    data.data.datasets.pop();
                    return data
                }),
               
            
        });



        $('#streams-date-range').daterangepicker({
            startDate: '{{ now()->subDays(7)->format('Y-m-d') }}',
            endDate: '{{ now()->format('Y-m-d') }}',
            maxDate: '{{ now()->format('Y-m-d') }}',
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        
        function updateStreamChart(e) {
            $("#stream-count").text('0')
            const dspId = $('#streams-dsp').val();
            const dateRange = $('.changealbumdate.active').attr('data-date');
            const ddt=`${streamsChartApiUrl}?dsp_id=${ dspId }&date_range=${dateRange}&user_id={{ $userId }}`;
            console.log(ddt);
            streamChart.update({
                url: `${streamsChartApiUrl}?dsp_id=${ dspId }&date_range=${dateRange}&user_id={{ $userId }}`
            })
        }
    </script>
    <script type="text/javascript">
        // $(document).on('click','.changealbum',function (e) {
        //     e.preventDefault();

        //     $("#stream-count").text('0');
        //     $('.changealbum').removeClass('active');
        //     $(this).addClass('active');

        //     const dspId = $(this).attr('data-value');
        //     const dateRange = $('.changealbumdate.active').attr('data-date');

        //     streamChart.update({
        //         url: `${streamsChartApiUrl}?dsp_id=${ dspId }&date_range=${dateRange}&user_id={{ $userId }}`
        //     })
        // });
        $(document).on('click','.changealbumdate',function (e) {
            e.preventDefault();

            $("#stream-count").text('0');
            $('.changealbumdate').removeClass('active');
            $(this).addClass('active');
            
            const dspId = $('#streams-dsp').val();
            const dateRange = $(this).attr('data-date');

            //alert(dateRange);
            streamChart.update({
                url: `${streamsChartApiUrl}?dsp_id=${ dspId }&date_range=${dateRange}&user_id={{ $userId }}`
            })
        });
    </script>
@endpush
