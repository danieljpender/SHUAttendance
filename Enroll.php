<!DOCTYPE html>
<html>
<head>
	<title>Module Enrollment List</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script>
	$(document).ready(function() {
		// Initialize the dropdown menus
		$.ajax({
			url: 'get-departments.php',
			success: function(data) {
				$('#department').html(data);
			}
		});

		// When a department is selected, populate the module dropdown
		$('#department').change(function() {
			var departmentId = $(this).val();
			$.ajax({
				url: 'get-modules.php',
				type: 'POST',
				data: { departmentId: departmentId },
				success: function(data) {
					$('#module').html(data);
				}
			});
		});

		// When a module is selected, display the enrollment list
		$('#module').change(function() {
			var moduleId = $(this).val();
			$.ajax({
				url: 'get-enrollment-list.php',
				type: 'POST',
				data: { moduleId: moduleId },
				success: function(data) {
					$('#enrollment-list').html(data);
				}
			});
		});
	});
	</script>
</head>
<body>
	<h1>Module Enrollment List</h1>
	<label for="department">Select a department:</label>
	<select id="department">
		<option value="">-- Select a department --</option>
	</select>
	<br><br>
	<label for="module">Select a module:</label>
	<select id="module">
		<option value="">-- Select a module --</option>
	</select>
	<br><br>
	<div id="enrollment-list"></div>
</body>
</html>
