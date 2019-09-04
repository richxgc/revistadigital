// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});
// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(statsLoad);

function statsLoad(){
	usersStats();
	curriculumStats();
}

function usersStats(){
	var width = $('#chart-users').parent('div').width();
	var data = null;
	$.ajax({
		url: path + '/estadisticas/get_users_stats',
		type: 'get',
		dataType: 'json',
		async: false,
		success: function(response){
			data = google.visualization.arrayToDataTable([
				['Usuarios','Sesiones','Registros'],
				['Usuarios', response.sessions,response.users],
			]);
		}, 
		error: function(){
			data = google.visualization.arrayToDataTable([
				['Usuarios','Cantidad',{role: 'style'}],
				['Sesiones', 0, '#2B5ABE'],
				['Registros', 0, '#83E20A']
			]);
		}
	});
	var options = {
		'title': 'Sesiones y Registros', 
		'titleTextStyle': {color:'#333',fontSize:15},
		'width': width, 'height': 300,
		chartArea:{left:20,top:40,width:"70%",height:"75%"},
		bar: {groupWidth: "40%"},
	};
	var chart = new google.visualization.ColumnChart(document.getElementById('chart-users'));
	chart.draw(data, options);
}

function curriculumStats(){
	var width = $('#chart-curriculums').parent('div').width();
	var data = new google.visualization.DataTable();
	data.addColumn('string','Tiene');
	data.addColumn('number','Curriculum');
	$.ajax({
		url: path + '/estadisticas/get_curriculum_count',
		type: 'get',
		dataType: 'json',
		async: false,
		success: function(response){
			data.addRows([['Tienen curriculum',response.yes],['No tienen curriculum',response.no]]);
		},
		error: function(){
			data.addRows([['Tienen curriculum',0],['No tienen curriculum',0]]);
		}
	});
	var options = {
		'title': 'Usuarios con Curriculum', 
		'titleTextStyle': {color:'#333',fontSize:15},
		'width': width, 'height': 270,
		chartArea:{left:15,top:40,width:"90%",height:"80%"},
		pieHole: 0.4,
	};
	var chart = new google.visualization.PieChart(document.getElementById('chart-curriculums'));
	chart.draw(data, options);
}