<?php

session_start();

include("../../connections.php");

if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    $authentication = mysqli_query($connections, "SELECT * FROM tbl_admin WHERE username='$username'");
    $fetch = mysqli_fetch_assoc($authentication);
    $account_type = $fetch["account_type"];

    if($account_type != 1 && $account_type != 2) {
        header("Location: ../../Forbidden.php");
        exit; // Ensure script stops executing after redirection
    }
} else {
    header("Location: ../../Forbidden.php");
    exit; // Ensure script stops executing after redirection
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Neon Admin Panel" />
	<meta name="author" content="" />

	<link rel="icon" type="image/jpg" href="../../img/Brgy. Estefania Logo (Old).png">

	<title>Barangay Estefania Admin - Driver ID System</title>

	<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="assets/css/bootstrap.css">
	<link rel="stylesheet" href="assets/css/neon-core.css">
	<link rel="stylesheet" href="assets/css/neon-theme.css">
	<link rel="stylesheet" href="assets/css/neon-forms.css">
	<link rel="stylesheet" href="assets/css/custom.css">

	<script src="assets/js/jquery-1.11.3.min.js"></script>

	<!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


</head>

<body class="page-body  page-left-in" data-url="http://neon.dev">

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
					
	<?php include "sidebar.php"?>

	<div class="main-content">
				
		<?php include "header.php" ?>
		
		<hr />
		
		<script type="text/javascript">
		jQuery(document).ready(function($)
		{
			$('.pie').sparkline('html', {
				type: 'pie',
				borderWidth: 0,
				sliceColors: ['#3d4554', '#ee4749','#00b19d']
			});
		
		
			$(".chart").sparkline([1,2,3,1], {
				type: 'pie',
				barColor: '#485671',
				height: '110px',
				barWidth: 10,
				barSpacing: 2});
		
			let map = $("#map");
		
			map.vectorMap({
				map: 'europe_merc_en',
				zoomMin: '3',
				backgroundColor: '#00a651',
				focusOn: { x: 0.5, y: 0.8, scale: 3 }
			});
		
		
		
			// Rickshaw
			let seriesData = [ [], [] ];
		
			let random = new Rickshaw.Fixtures.RandomData(50);
		
			for (let i = 0; i < 90; i++)
			{
				random.addData(seriesData);
			}
		
			let graph = new Rickshaw.Graph( {
				element: document.getElementById("rickshaw-chart-demo-2"),
				height: 217,
				renderer: 'area',
				stroke: false,
				preserve: true,
				series: [{
						color: '#359ade',
						data: seriesData[0],
						name: 'Page Views'
					}, {
						color: '#73c8ff',
						data: seriesData[1],
						name: 'Unique Users'
					}, {
						color: '#e0f2ff',
						data: seriesData[1],
						name: 'Bounce Rate'
					}
				]
			} );
		
			graph.render();
		
			let hoverDetail = new Rickshaw.Graph.HoverDetail( {
				graph: graph,
				xFormatter: function(x) {
					return new Date(x * 1000).toString();
				}
			} );
		
			let legend = new Rickshaw.Graph.Legend( {
				graph: graph,
				element: document.getElementById('rickshaw-legend')
			} );
		
			let highlighter = new Rickshaw.Graph.Behavior.Series.Highlight( {
				graph: graph,
				legend: legend
			} );
		
			setInterval( function() {
				random.removeData(seriesData);
				random.addData(seriesData);
				graph.update();
		
			}, 500 );
		
		});
		</script>
		
		
		<?php include "dashboard.php" ?>
		
		

<?php include "footer.php" ?>


	<!-- Imported styles on this page -->
	<link rel="stylesheet" href="assets/js/jvectormap/jquery-jvectormap-1.2.2.css">
	<link rel="stylesheet" href="assets/js/rickshaw/rickshaw.min.css">

	<!-- Bottom scripts (common) -->
	<script src="assets/js/gsap/TweenMax.min.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>


	<!-- Imported scripts on this page -->
	<script src="assets/js/jvectormap/jquery-jvectormap-europe-merc-en.js"></script>
	<script src="assets/js/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
	<script src="assets/js/jquery.sparkline.min.js"></script>
	<script src="assets/js/rickshaw/vendor/d3.v3.js"></script>
	<script src="assets/js/rickshaw/rickshaw.min.js"></script>
	<script src="assets/js/neon-chat.js"></script>


	<!-- JavaScripts initializations and stuff -->
	<script src="assets/js/neon-custom.js"></script>


	<!-- Demo Settings -->
	<script src="assets/js/neon-demo.js"></script>

</body>
</html>