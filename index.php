<?php
	include("functions.php");

	$message="";
	$fileManipulationObj=new FileManipulation;

	if(isset($_REQUEST['delFileName']) && $_REQUEST['uploadFile'] != "") {
		
		$safeFileID=strip_tags($_REQUEST['uploadFile']);
		$message=$fileManipulationObj->deleteFile($safeFileID);
		
	} else {
		if (isset($_REQUEST['submit']) && $_REQUEST['submit'] !="") {
			
			$message=$fileManipulationObj->handleFile($_FILES, session_id());
			
			
		 } else {
			
			$message="Please upload your file below!";
			
		}
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Week 11 - Form File Upload Submission</title>
</head>
	<link rel="stylesheet" type="text/css" href="style.css">

<body>
	<div id="main">
		<div id="windowBox">

			<h3>Week 11 - Form File Upload Submission</h3>
			<hr>
			<p><?php echo $message; ?></p>
			
				<div id="formsDiv">
					<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
						<table>
							<tr>
								<td><span id="spanfileupload">Choose a picture or document to upload.</span></td>
							</tr>
							<tr>
								<td>
									<input type="file" name="uploadFile">
								</td>
							</tr>
							<tr><td><hr></td></tr>
							<tr>
								<td><span id="submission">Submit your file.</span></td>
							</tr>
							<tr>
								<td>
									<input type="hidden" name="max_file_size" value="1000000"/>
									<input name="submit" type="submit" value="Upload File">
								</td>
							</tr>
							
								<?php 
									if (isset($fileManipulationObj->filesListing)) {
										
										echo "<tr><td>";
										//echo $fileManipulationObj->getPath();
										echo "<h3>Listing of files:</h3>";
										echo "<hr></td></tr>";
										
										foreach ($fileManipulationObj->filesListing as $loop) {
											echo "<tr><td><a href='". "uploads/".$loop ."'>".$loop."</a></td></tr>";	
										}
									}
								?>
							

						</table>
					</form>
				</div>
		</div>

	</div>

</body>

</html>