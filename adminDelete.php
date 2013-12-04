<?php
require_once("connect.php");
//This document allows for a user to input their data into the form. The form is then checked for error
//and is then sent to the database once error checking is completed and no errors found.


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// initialize my variables
//
$debug=false;

if(isset($_GET["q"])){ 
    $serviceNum = htmlentities($_GET["q"], ENT_QUOTES, "UTF-8");
	// Retrieve the information
    // 
		$sql = "DELETE FROM tblService WHERE pkServiceNum=" . $serviceNum; 
            $stmt = $db->prepare($sql); 
            $stmt->execute();   
			
		$sql = "DELETE FROM tblUpdate WHERE fkServiceNum=" . $serviceNum;
            $stmt = $db->prepare($sql);
            $stmt->execute(); 
	
		
		// If the transaction was successful, give success message 
	//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
	
}
?>

<? include ("top.php"); ?>
 
 <body id="form">
 
 <section id="bgColor" class="rounded-corners border">
 <? include ("header.php"); ?>
 <? include ("nav.php"); ?>
 
 <section id="content">  
	<? 
		Print "<h3>Record Number " . $serviceNum . " has been permanently deleted</h2>";
		print '<p><a href="http://www.uvm.edu/~isimon/cs148/assignment7.1/admin.php">Return to Admin Page</a></p>';
	?>
 <? include ("footer.php"); ?>
 
 </section>
 
 </html>
 </body>