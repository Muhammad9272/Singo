@props(['userId' => null])

<div>
    <div class="row mb-2">
        <div class="col-md-6">
            <div class="form-group">
                <label for="">Date Range</label>
                <input type="text" data-type="date-range" id="streams-date-range" class="form-control" onchange="updateStreamChart(this)">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="">DSP</label>
                <x-form.select2-dsp name="streams-dsp" id="streams-dsp" onchange="updateStreamChart(this)"/>
            </div>
        </div>
    </div>
    <p class="text-right text-sm text-info">Total Streams: <span id="stream-count">0</span></p>
    <div id="artist_stream_chart" style="height: 300px;"></div>
</div>

@push('page_scripts')
    <script>
        let streamsChartApiUrl = `@chart('artist_streams_chart')`;

        var nf = Intl.NumberFormat();
        let streamChart = new Chartisan({
            el: '#artist_stream_chart',
            url: `${streamsChartApiUrl}?user_id={{ $userId }}`,
            hooks: new ChartisanHooks()
                .beginAtZero()
                .colors()
                .responsive(true)
                .datasets([
                    {
                        type: 'line',
                        fill: false,
                        tension: 0
                    }
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
            const dateRange = $('#streams-date-range').val();

            streamChart.update({
                url: `${streamsChartApiUrl}?dsp_id=${ dspId }&date_range=${dateRange}&user_id={{ $userId }}`
            })
        }
    </script>
@endpush
