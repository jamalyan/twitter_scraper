import {Component} from '@angular/core';
import {ChartOptions, ChartType} from 'chart.js';
import {DatabaseService, TweetObject} from "./services/database.service";

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css']
})
export class AppComponent {
    public data_sets: object [];
    public data_labels: object [];
    public chartReady = false;

    public barChartOptions: ChartOptions = {
        responsive: true,
        animation: {
            onComplete: function () {
                let chartInstance = this.chart,
                    ctx = chartInstance.ctx;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'bottom';
                this.data.datasets.forEach(function (dataset, i) {
                    let meta = chartInstance.controller.getDatasetMeta(i);
                    meta.data.forEach(function (bar, index) {
                        let data = dataset.data[index];
                        ctx.fillText(data + ' ' + dataset.label, bar._model.x, bar._model.y - 10);
                    });
                });
            }
        }
    };

    public barChartType: ChartType = 'bar';
    public barChartLegend = true;
    public barChartPlugins = [];

    constructor(private databaseService: DatabaseService) {
    }

    ngOnInit() {
        this.databaseService.getTweets().subscribe((data: TweetObject) => {
            this.data_sets = data.sets;
            this.data_labels = data.labels;
            this.chartReady = true;
        })
    }
}
