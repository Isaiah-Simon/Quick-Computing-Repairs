<?php
require_once("connect.php");
//This document allows for a user to input their data into the form. The form is then checked for error
//and is then sent to the database once error checking is completed and no errors found.


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// initialize my variables
//
$debug=false;

$serviceNum="";
$firstName="";
$lastName="";
$phoneNumber="";
$emailAddress="";
$street="";
$city="";
$state="";
$zip="";
$priority="";
$platform="";
$operatingSystem="";
$problemDueTo="";
$specificPointOfFailure="";
$shortDescription="";
$dateCreated="";
$status="";
$updates="";
$update="";
$updateTime="";

$baseURL = "https://www.uvm.edu/~isimon/cs148/assignment7.1/";
$yourURL = $baseURL . "admin/adminView.php";

//initialize flags for errors, one for each item
$serviceNumERROR = false;
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// if form has been submitted, validate the information
if(isset($_GET["q"])){ 
    $serviceNum = htmlentities($_GET["q"], ENT_QUOTES, "UTF-8");
	// Retrieve the information
    // 
		$sql = "SELECT * FROM tblService, tblPerson WHERE pkserviceNum=" . $serviceNum; 
            $stmt = $db->prepare($sql); 
            $stmt->execute(); 

            $result = $stmt->fetch(PDO::FETCH_ASSOC); 
             
			$shortDescription = $result["fldShortDescription"];
			$status = $result["fldStatus"];
			$priority = $result["fldPriority"];
			$firstName = $result["fldFirstName"];
			$lastName = $result["fldLastName"];
			$emailAddress = $result["pkEmailAddress"];
			$phoneNumber = $result["fldPhoneNumber"];
			$street = $result["fldStreet"];
			$city = $result["fldCity"];
			$state = $result["fldState"];
			$zip = $result["fldZip"];
			$platform = $result["fldPlatform"];
			$operatingSystem = $result["fldOperatingSystem"];
			$problemDueTo = $result["fldProblemDueTo"];
			$specificPointOfFailure = $result["fldSpecificPointOfFailure"];
			$dateCreated = $result["fldDateCreated"];
			
		$sql = "SELECT fldUpdateText,fldTimeEdited FROM tblUpdate WHERE fkserviceNum=" . $serviceNum . "  ORDER BY fldTimeEdited desc";
            $stmt = $db->prepare($sql);
            $stmt->execute(); 
			$updates = $stmt->fetchAll();
	
		
		// If the transaction was successful, give success message 
	//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
	
}
?>

<? include ("top.php"); ?>
 
 <body id="adminView">
 
 <section id="bgColor" class="rounded-corners border">
 <? include ("header.php"); ?>
 <? include ("nav.php"); ?>
 
 <section id="content">  
 
 <section>
   <h2>Admin View</h2>
		<?
			print '<p><b>Service ID Number:</b><i>   ' . $serviceNum . '</i><b> Short Description:</b><i> ' . $shortDescription . '</i></p>';
			print '<p><b>Status:</b><i>   ' . $status . '</i><b> Priority:</b><i> ' . $priority . '</i></p>';
			print '<p><b>First Name:</b><i>   ' . $firstName . '</i><b> Last Name:</b><i> ' . $lastName . '</i></p>';
			print '<p><b>Email Address:</b><i>   ' . $emailAddress . '</i><b> Phone Number:</b><i> ' . $phoneNumber . '</i></p>';
			print '<p><b>Street:</b><i>   ' . $street . '</i><b> City:</b><i> ' . $city . '</i></p>';
			print '<p><b>State:</b><i>   ' . $state . '</i><b> Zip:</b><i> ' . $zip . '</i></p>';
			print '<p><b>Platform:</b><i>   ' . $platform . '</i><b> Operating System:</b><i> ' . $operatingSystem . '</i></p>';
			print '<p><b>Problem Due To:</b><i>   ' . $problemDueTo . '</i><b> Specific Point of Failure:</b><i> ' . $specificPointOfFailure . '</i></p>';
			print "<p><b>Updates:<i> Most recent updates are shown first</i></b></p>";
			print "<table>\n";
			print "<tr>\n";
			print "</tr>\n";

			// -----------------------------------------------------------------------------
			// print out the records
			foreach ($updates as $update) {
				print "<tr>\n";
				print "<td><b>" . $update['fldTimeEdited'] . "</b></td>\n";
				print "<td><i>" . $update['fldUpdateText'] . "</i></td>\n";
				print "</tr>\n";
			}
			print "</table>\n";
			?>
 </section>
 
 <? include ("footer.php"); ?>
 
 </section>
 
 </html>
 </body>