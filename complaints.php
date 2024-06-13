<?php
	
	if (isset($action) && $action != '') {
		$stmt = $MySQL->prepare("SELECT * FROM complaints WHERE id = ?");
		$stmt->bind_param("i", $_GET['action']);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_array();
		$stmt->close();
			print '
			<div class="complaints">
			<h2>' . $row['title'] . '</h2>
			<p>'  . $row['description'] . '</p>
			<time datetime="' . $row['date'] . '">' . pickerDateToMysql($row['date']) . '</time>
			<img src="complaint_images/' . $row['picture'] . '" alt="' . $row['title'] . '" title="' . $row['title'] . '">
				<hr>
			</div>';
	}
	else {
		print '<h1>Complaints</h1>';
		$stmt = $MySQL->prepare("SELECT * FROM complaints ORDER BY date DESC");
		$stmt->execute();
		$result = $stmt->get_result();
		print '<div class="container">';
		$stmt->close();
		while($row = @mysqli_fetch_array($result)) {
			print '
			<div class="complaint-item">
				<img src="complaint_images/' . $row['picture'] . '" alt="' . $row['title'] . '" title="' . $row['title'] . '">
				<div class="content">
                    <h2 class = title>' . $row['title'] . '</h2>';
                    print '<p class="description">';
				    if(strlen($row['description']) > 300) {
					    echo substr(strip_tags($row['description']), 0, 300).'... <a href="index.php?menu=' . $menu . '&amp;action=' . $row['id'] . '">More</a>';
				    } else {
					    echo strip_tags($row['description']);
				    }
                    print '</p>';
				    print '
				    <time datetime="' . $row['date'] . '">' . pickerDateToMysql($row['date']) . '</time>
				</div>
			</div>';
		}

        print '</div>';
	}
?>