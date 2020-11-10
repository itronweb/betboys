/* ------------------------------------------------------------------------------
 *
 *  # Dashboard configuration
 *
 *  Demo dashboard configuration. Contains charts and plugin inits
 *
 *  Version: 1.0
 *  Latest update: Aug 1, 2015
 *
 * ---------------------------------------------------------------------------- */

$(function() {    


    // Donut chart
    // ------------------------------

    // Generate chart
    var donut_chart = c3.generate({
        bindto: '#c3-donut-chart',
        size: { width: 350 },
        data: {
			x: 'browser',
			url: 'assets/data/browser_chart.json',
			mimeType: 'json',
            type : 'donut'
        },
        donut: {
            title: "مرورگر"
        }
    });


 



    // Pie chart
    // ------------------------------

    // Generate chart
    var pie_chart = c3.generate({
        bindto: '#c3-pie-chart',
        size: { width: 350 },
        color: {
            pattern: ['#2ca02c', '#d62728', '#17becf', '#9467bd', '#7f7f7f','#e377c1', '#8c564b', '#2077b4', '#ff7f0f', '#ac0152', '#0600ff']
        },
        data: {
            x: 'os',
			url: 'assets/data/os_chart.json',
			mimeType: 'json',
            type : 'pie'
        }
    });



    // Categorized chart
    // ------------------------------
	var chart = c3.generate({
		bindto: '#c3-axis-categorized',
		point: { 
			r: 4   
		},
		size: { height: 400 },
		zoom: {
			enabled: false
		},
		data: {
					x: 'date',
					url: 'assets/data/counter_chart.json',
					mimeType: 'json',
		},
		axis: {
			x: {
			   type: 'category',
				height: 100,
				tick: {
                rotate: -65,
                multiline: false
				},
			}
		},
		color: {
			pattern: ['rgb(94, 53, 177)', 'rgb(229, 57, 53)']
		},
        grid: {
            x: {
                show: false
            }
        }
	});

});
