<style>
	body 		{font-family:Verdana;}
	td, th		{border: 1px solid black; padding:2px; font-size:12px;} 
	th 			{background-color:#ddd; font-weight:normal;} 
</style>
<h2><?php echo $testName; ?></h2>
<table style="border-collapse:collapse">
	<thead>
		<tr>
			<th>Test Name</th>
			<th>Test Datatype</th>
			<th>Expected Datatype</th>
			<th>Result</th>
			<th>File Name</th>
			<th>Line Number</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php echo $report; ?>
	</tbody>
</table>