/**
 * @author Abdelkader Benkhadra
*/

// This function uses the Highcharts library to display a chart 
// depending on the arguments passed to it.
// cls : represents the class that will be passed to the ajax call.
// Data will be fetched according to the class passed as argument
// title : reprents the title of the chart
// container : represents the div that will hold the chart
// series_names : represents the names of each series
function createChart(cls, title, container, series_names) {
    var chart,
        date = [], // Stores the dates as categories for the xAxis
        months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août",
                  "Septembre", "Octobre", "Novembre", "Décembre"];
    
    $(document).ready(function() {
        $.getJSON("/app/common/ajax.php?class="+ cls, function(data) {

            // Load all the existent series
            function loadSeries()
            {
                for(var i = 2, j = 0; i < data.length, j < series_names.length; i++, j++)
                {
                    chart.addSeries({name: series_names[j],  data : data[i]});
                }
            }

            // Format the date in the xAxis
            for(var i = 0; i < data[0].length; i++)
            {
                date.push(months[data[1][i] - 1] + ' ' + data[0][i]);
            }

            // Convert all the elements of the series to Integers since we get Strings 
            for(i = 2; i < data.length; i++)
            {
                convertToInt(data[i]);
            }

            // Create the chart
            chart = new Highcharts.Chart({
                chart: {
                    renderTo: container,
                    type: 'spline',
                    events: { 
                        load: function() {
                                chart = this;
                                loadSeries();
                        }
                    },
                    plotBorderWidth: 1,
                    plotBorderColor: '#3F4044',
                    marginTop: 10,
                    marginRight: 0,
                    spacingLeft: 30,
                    spacingBottom: 0
                },
                title: {
                    text: title
                },
                subtitle: {
                    text: 'Depuis ' + date[0]
                },
                xAxis: {
                    title: {
                        text: 'Date'
                    },
                    categories: date, // This is the dates array
                    tickInterval: 1,
                    gridLineDashStyle: 'dot',
                    gridLineWidth: 1,
                    lineWidth: 2,
                    lineColor: '#92A8CD',
                    tickWidth: 3,
                    tickLength: 6,
                    tickColor: '#92A8CD'
                },
                yAxis: {
                    title: {
                        text: 'Nombre'
                    },
                    min: 0,
                    gridLineColor: "#8AB8E6",
                    alternateGridColor: {
                        linearGradient: {
                            x1: 0, y1: 1,
                            x2: 1, y2: 1
                        },
                        stops: [ [0, '#FAFCFF'],
                                 [0.5, '#F5FAFF'],
                                 [0.8, '#E0F0FF'],
                                 [1, '#D6EBFF'] ]
                    },
                    lineWidth: 2,
                    lineColor: '#92A8CD',
                    tickWidth: 3,
                    tickLength: 6,
                    tickColor: '#92A8CD'
                },
                tooltip : {
                    crosshairs: [{
                        color: '#8D8D8D',
                        dashStyle: 'dash'
                    }, {
                        color: '#8D8D8D',
                        dashStyle: 'dash'
                    }],
                    formatter: function() {
                        return '<span style="color:#039;font-weight:bold">' + this.point.category + 
                               '</span><br/><span style="color:' + this.series.color + '">' +
                               this.series.name + '</span>: <span style="color:#D15600;font-weight:bold">' + 
                               this.point.y + '</span>';
                    }
                },    
                plotOptions: {
                    series: {
                        lineWidth: 2
                    }
                },
                series: []
            });
        });
    
        // Convert to Integers
        function convertToInt(array) {
            for(var i = 0; i < array.length; i++)
            {
                array[i] = parseInt(array[i], 10);
            }
        }
    });
}

// Create the users chart
users_series = ['Utilisateurs trial', 'Utilisateurs payants'];
createChart("Users", "Evolution du nombre d'utilisateurs", "users_chart", users_series);
// Create the sites chart
sites_series = ['Nombre de pages', 'Nombre de visites'];
createChart("Sites", "Evolution du nombre de pages et de visites", "sites_chart", sites_series);
// Create the business chart
business_series = ['Utilisateurs prospectés', 'Nouveaux utilisateurs', "Nombre total d'utilisateurs", 'Catégories de métiers'];
createChart("Business", "Evolution du business", "business_chart", business_series);
