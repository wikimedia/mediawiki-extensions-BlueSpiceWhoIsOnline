(function ( mw, $, bs) {
	bs.util.registerNamespace( 'bs.whoisonline.report' );

	bs.whoisonline.report.LoginDurationReport = function ( cfg ) {
		bs.whoisonline.report.LoginDurationReport.parent.call( this, cfg );
	};

	OO.inheritClass( bs.whoisonline.report.LoginDurationReport, bs.aggregatedStatistics.report.ReportBase );

	bs.whoisonline.report.LoginDurationReport.static.label = mw.message( 'bs-whoisonline-statistics-report-login-duration' ).text();

	bs.whoisonline.report.LoginDurationReport.prototype.getFilters = function () {
		return [
			new bs.aggregatedStatistics.filter.IntervalFilter(),
			new bs.aggregatedStatistics.filter.UserMultiFilter( { required: true } )
		];
	};

	bs.whoisonline.report.LoginDurationReport.prototype.getChart = function () {
		return new bs.aggregatedStatistics.charts.LineChart();
	};

	bs.whoisonline.report.LoginDurationReport.prototype.getAxisLabels = function () {
		return {
			value: mw.message( "bs-whoisonline-statistics-report-login-duration-axis-value" ).text()
		};
	};

} )( mediaWiki, jQuery , blueSpice);