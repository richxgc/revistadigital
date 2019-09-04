// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});
// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(statsLoad);

function statsLoad(){
	categoryArticlesStats();
	mostReadStats();
	monthlyPublicationsStats();
}

function categoryArticlesStats(){
	var width = $('#chart-category-articles').parent('div').width();
	var data = null;
	var slices = [];
	$.ajax({
		url: path + '/estadisticas/get_category_articles',
		type: 'get',
		dataType: 'json',
		async: false,
		success: function(response){
			tmp_data = [['Categoria', 'Artículos']];
			$.each(response, function(index, category) {
				tmp_data.push([category.cat_nombre,parseInt(category.cat_articulos)]);
				slices.push({color:category.cat_color});
			});
			data = google.visualization.arrayToDataTable(tmp_data);
		}, 
		error: function(){
			data = google.visualization.arrayToDataTable([['Categorias', 'Artículos']]);
		}
	});
	var options = {
		title: 'Artículos publicados por categoría',
		'titleTextStyle': {color:'#333',fontSize:15},
		'width': width, 'height': 280,
		chartArea:{left:20,top:40,width:"90%",height:"75%"},
		pieHole: 0.4,
		slices: slices,
	}
	var chart = new google.visualization.PieChart(document.getElementById('chart-category-articles'));
	chart.draw(data,options);
}

function mostReadStats(){
	var width = $('#chart-most-read').parent('div').width();
	var data = null;
	$.ajax({
		url: path + '/estadisticas/get_most_read_articles',
		type: 'get',
		dataType: 'json',
		async: false,
		success: function(response){
			tmp_data = [['Artículo', 'Veces']];
			$.each(response, function(index, article) {
				tmp_data.push([article.art_titulo,parseInt(article.art_leido)]);
			});
			data = google.visualization.arrayToDataTable(tmp_data);
		}, 
		error: function(){
			data = google.visualization.arrayToDataTable([['Artículo', 'Veces']]);
		}
	});
	var options = {
		title: 'Los artículos más leidos',
		'titleTextStyle': {color:'#333',fontSize:15},
		'width': width, 'height': 280,
		chartArea:{left:20,top:40,width:"90%",height:"75%"},
		pieHole: 0.4,
		pieSliceText: 'value',
	}
	var chart = new google.visualization.PieChart(document.getElementById('chart-most-read'));
	chart.draw(data,options);
}

function monthlyPublicationsStats(){
	var width = $('#chart-monthly-publications').parent('div').width();
	var data = null;
	var average = [];
	$.ajax({
		url: path + '/estadisticas/get_monthly_publications',
		type: 'get',
		dataType: 'json',
		async: false,
		success: function(response){
			average[(response.header.length)] = {type: 'line'};
			var data_header = ['Mes'];
			$.each(response.header, function(index, category) {
				data_header.push(category.cat_nombre);
			});
			data_header.push('Promedio');
			var tmp_data = [data_header];
			$.each(response.months, function(index, month) {
				var tmp_month = [];
				$.each(month, function(index, val) {
					tmp_month.push(val);
				});
				tmp_data.push(tmp_month);
			});
			data = google.visualization.arrayToDataTable(tmp_data);
		}, 
		error: function(){
			data = google.visualization.arrayToDataTable([]);
		}
	});
	var options = {
		'title': 'Publicación de artículos en los últimos 3 meses', 
		'titleTextStyle': {color:'#333',fontSize:15},
		'width': width, 'height': 280,
		chartArea:{left:40,top:40,width:"80%",height:"70%"},
		vAxis: {title: 'Artículos publicados'},
		hAxis: {title: 'Mes'},
		seriesType: 'bars',
		series: average,
	};
	var chart = new google.visualization.ComboChart(document.getElementById('chart-monthly-publications'));
	chart.draw(data, options);
}