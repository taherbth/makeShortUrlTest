<script>
    import { Line } from "vue-chartjs";

    export default {
        extends: Line,
        data() {
            return {
            gradient: null,
            gradient2: null
            };
        },
        props:{
            chartData: { type: Object },
        },
        mounted() {
            this.gradient = this.$refs.canvas.getContext("2d").createLinearGradient(0, 0, 0, 450);
            this.gradient2 = this.$refs.canvas.getContext("2d").createLinearGradient(0, 0, 0, 450);

            this.gradient.addColorStop(0, "rgba(211, 217, 220, 0.50)");
            this.gradient.addColorStop(0.5, "rgba(211, 217, 220, 0.25)");
            this.gradient.addColorStop(1, "rgba(211, 217, 220, 0)");

            this.gradient2.addColorStop(0, "rgba(255, 170, 56, 0.50)");
            this.gradient2.addColorStop(0.5, "rgba(255, 170, 56, 0.25)");
            this.gradient2.addColorStop(1, "rgba(255, 170, 56, 0)");


            this.renderChart(
                {
                    labels: this.chartData.labels,
                    datasets: [
                        {
                            label: "Video watched",
                            borderColor: "#D3D9DC",
                            pointBackgroundColor: "white",
                            borderWidth: 1,
                            pointBorderColor: "white",
                            backgroundColor: this.gradient,
                            data: this.chartData.watched
                        },
                        {
                            label: "Responses",
                            borderColor: "#FFAA38",
                            pointBackgroundColor: "white",
                            pointBorderColor: "white",
                            borderWidth: 1,
                            backgroundColor: this.gradient2,
                            data: this.chartData.responses
                        }
                    ],
                },
                {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'bottom',
                        align: 'start',
                        labels: {
                            fontColor: '#909FA8'
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                fontColor: '#909FA8'
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontColor: '#909FA8',
                            }
                        }]
                    }
                }
            );
        }
    };
</script>
