<template>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6 pt-3"><h5>Статистика пользователей за месяц</h5></div>
                <div class="col-6">
                </div>
            </div>
        </div>

        <div class="card-body table-responsive p-0">
            <div class="progress" v-if="progress != -1">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" :style="`width: ${progress}%`"></div>
            </div>
            <div style="height: 500px">
                <line-chart
                v-if="chartLoaded"
                :chartdata="chartData"
                :options="chartOptions"/>
            </div>                
        </div>
        <!-- /.card-body -->
        <div class="card-footer">

        </div>
    </div>
</template>

<script>
    import LineChart from './Chart.vue'

    export default {
        components: { LineChart },
        data() {
            return {
                limit: 10,
                showDisabled: false,
                size: 'default',
                align: 'center',
                progress: -1,
                loadingTimer: 600,
                search: "",
                chartLoaded: false,
                chartOptions: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                // min: 6000
                            }
                        }]
                    }
                },
                chartData: null
            }
        },
        async mounted() {
            this.getResults();
        },
        methods: {
            progressTick() {
                if (this.progress > 85) {
                    return this.progress;
                } else {
                    setTimeout(() => {
                        this.progress += 15;
                        this.progressTick();
                    }, this.loadingTimer);
                }
            },
            getResults() {
                this.progress = 0;
                this.progressTick();
                this.chartLoaded = false;
                axios.get('/stat/metrika')
                    .then(response => {
                        // console.log(response);
                        this.progress = 100;
                        setTimeout(() => {
                            this.progress = -1;
                        }, this.loadingTimer);
                        this.chartData = response.data
                        this.chartLoaded = true;
                        console.log("this.chartData", this.chartData);
                    })
					.catch(err => console.log(err.response.data))
					.finally(() => (this.progress = -1));
            },

        },
    }
</script>