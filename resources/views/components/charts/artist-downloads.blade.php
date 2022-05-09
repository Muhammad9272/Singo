@props(['userId' => null])

<div>
    <div class="row mb-2">
        <div class="col-md-6">
            <div class="form-group">
                <label for="">Date Range</label>
                <input type="text" data-type="date-range" id="downloads-date-range" class="form-control" onchange="updateDownloadsChart(this)">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="">DSP</label>
                <x-form.select2-dsp name="downloads-dsp" id="downloads-dsp" onchange="updateDownloadsChart(this)"/>
            </div>
        </div>
    </div>

    <p class="text-right text-sm text-info">Total Downloads: <span id="download-count">0</span></p>
    <div id="artist_downloads_chart" style="height: 300px;"></div>
</div>

@push('page_scripts')
    <script>
        let downloadsChartApiUrl = `@chart('artist_downloads_chart')`;

        var nf = Intl.NumberFormat();
        let downloadsChart = new Chartisan({
            el: '#artist_downloads_chart',
            url: `${downloadsChartApiUrl}?user_id={{ $userId }}`,
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
                    $("#download-count").text(totalCount)
                    data.data.datasets.pop();
                    return data
                }),
        });

        $('#downloads-date-range').daterangepicker({
            startDate: '{{ now()->subDays(7)->format('Y-m-d') }}',
            endDate: '{{ now()->format('Y-m-d') }}',
            maxDate: '{{ now()->format('Y-m-d') }}',
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        function updateDownloadsChart(e) {
            $("#download-count").text('0')
            const dspId = $('#downloads-dsp').val();
            const dateRange = $('#downloads-date-range').val();

            downloadsChart.update({
                url: `${downloadsChartApiUrl}?dsp_id=${ dspId }&date_range=${dateRange}&user_id={{ $userId }}`
            })
        }
    </script>
@endpush
