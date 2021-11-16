
<?php
function error_without_field($message)
{
	return '<script>
					document.getElementById("error").innerHTML = "' . $message . '";
					document.getElementById("error").className += "alert alert-danger";

				</script>';
}

function error_with_field($message, $field)
{
	return '<script>
					document.getElementById("error").innerHTML = "' . $message . '";
					document.getElementById("error-message").style.display = "block";
					document.getElementById("' . $field . '").className += "alert alert-danger";
				</script>';
}

function success($message)
{
	return '<script>
					document.getElementById("error").innerHTML = "' . $message . '";
					document.getElementById("error").className += "alert alert-success";
				</script>';
}
