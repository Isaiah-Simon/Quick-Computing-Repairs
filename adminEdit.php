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

$baseURL = "http://www.uvm.edu/~isimon/cs148/assignment7.1/";
$yourURL = $baseURL . "search.php";

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
			print $sql;

            $result = $stmt->fetch(PDO::FETCH_ASSOC); 
             
			$shortDescription = $result["fldShortDescription"];
			$status = $result["fldStatus"];
			$priority = $result["fldPriority"];
			$firstName = $result["fldFirstName"];
			$lastName = $result["fldLastName"];
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
 
 <body id="form">
 
 <section id="bgColor" class="rounded-corners border">
 <? include ("header.php"); ?>
 <? include ("nav.php"); ?>
 
 <section id="content">  
 
 <section>
   <h2>Admin Edit</h2>
		<form action="<? print $_SERVER['PHP_SELF']; ?>" method="post" id="frmAdminEdit" enctype="multipart/form-data">
		<p><b>Required fields are marked with an asterisks (*). </b><p>
		<fieldset>
			<label for="txtShortDescription" class="required">Short Description *</label>
		   <input type="text" id="txtShortDescription" name="txtShortDescription" value="<?php echo $shortDescription; ?>" tabindex="1"
					size="75" maxlength="75" placeholder="Short Description" <?php if($shortDescriptionERROR) echo 'class="mistake"' ?>
					onfocus="this.select()" />
			<br />
			<label for="lstPriority">Priority Level:</label>
			<select id="lstPriority" name="lstPriority" tabindex="15" size="1">
				<option value="Not Set" <?php if($priority=="Not Set") echo ' selected="selected" ';?> >Not Set</option>
				<option value="Normal" <?php if($priority=="Normal") echo ' selected="selected" ';?> >Normal</option>
				<option value="Emergency" <?php if($priority=="Emergency") echo ' selected="selected" ';?> >Emergency</option>
				<option value="Pressing" <?php if($priority=="Pressing") echo ' selected="selected" ';?> >Pressing</option>
			</select>
		</fieldset>
		
		<!-- Creates First Name text box for user input -->
		<fieldset>               
		   <label for="txtFname" class="required">First Name *</label>
		   <input type="text" id="txtFname" name="txtFname" value="<?php echo $firstName; ?>" tabindex="1"
					size="25" maxlength="45" placeholder="Please enter your first name" <?php if($firstNameERROR) echo 'class="mistake"' ?>
					onfocus="this.select()" />
					
		   <!-- Creates Last Name text box for user input -->         
		   <label for="txtLname" class="required">Last Name *</label>
		   <input type="text" id="txtLname" name="txtLname" value="<?php echo $lastName; ?>" tabindex="2"
					size="25" maxlength="45" placeholder="Please enter your last name" <?php if($lastNameERROR) echo 'class="mistake"' ?>
					onfocus="this.select()" />
		</fieldset>
		<fieldset>  
			<!-- Creates Email text box for user input -->
		   <label for="txtEmail" class="required">Email Address *</label>
		   <input type="email" id="txtEmail" name="txtEmail" value="<?php echo $emailAddress; ?>" tabindex="3"
					size="35" maxlength="45" placeholder="Please enter a valid Email address" <?php if($emailAddressERROR) echo 'class="mistake"' ?>
					onfocus="this.select()" />
			<br>
			<!-- Creates Phone Number text box for user input -->
			<label for="txtPhone" class="required">Phone Number * (No Dashes)</label>
		   <input type="tel" id="txtPhone" name="txtPhone" value="<?php echo $phoneNumber; ?>" tabindex="4"
					size="30" maxlength="10" placeholder="Please enter your Phone Number" <?php if($phoneNumberERROR) echo 'class="mistake"' ?>
					onfocus="this.select()" />
		</fieldset> 
		
		<fieldset>  
			<label for="txtStreet" class="required">Street *</label>
			<input type="text" id="txtStreet" name="txtStreet" value="<?php echo $street; ?>" tabindex="7"
					size="35" maxlength="45" placeholder="Please enter your Street Address" <?php if($streetERROR) echo 'class="mistake"' ?>
					onfocus="this.select()" />
					
			<label for="txtCity" class="required">City *</label>
		   <input type="text" id="txtCity" name="txtCity" value="<?php echo $city; ?>" tabindex="7"
					size="25" maxlength="35" placeholder="Please enter your City" <?php if($cityERROR) echo 'class="mistake"' ?>
					onfocus="this.select()" />
			<br>
			<label for="txtState" class="required">State *</label>
			<input type="text" id="txtState" name="txtState" value="<?php echo $state; ?>" tabindex="7"
					size="20" maxlength="20" placeholder="Please enter your State" <?php if($stateERROR) echo 'class="mistake"' ?>
					onfocus="this.select()" />
					
			<label for="txtZip" class="required">Zip Code *</label>
			<input type="text" id="txtZip" name="txtZip" value="<?php echo $zip; ?>" tabindex="7"
					size="25" maxlength="10" placeholder="Please enter your Zip Code" <?php if($zipERROR) echo 'class="mistake"' ?>
					onfocus="this.select()" />
		</fieldset>
		
		<fieldset>
		<label for="lstPlatform">Platform:</label>
			<select id="lstPlatform" name="lstPlatform" tabindex="15" size="1">
				<option value="Not Set" <?php if($platform=="Not Set") echo ' selected="selected" ';?> >Not Set</option>
				<option value="Desktop/Laptop" <?php if($platform=="Desktop/Laptop") echo ' selected="selected" ';?> >Desktop/Laptop</option>
				<option value="Phone/Tablet" <?php if($platform=="Phone/Tablet") echo ' selected="selected" ';?> >Phone/Tablet</option>
				<option value="Media/Game Console" <?php if($platform=="Media/Game Console") echo ' selected="selected" ';?> >Media/Game Console</option>
				<option value="Other" <?php if($platform=="Other") echo ' selected="selected" ';?> >Other</option>
			</select>
		<label for="lstOperatingSystem">Operating System:</label>
			<select id="lstOperatingSystem" name="lstOperatingSystem" tabindex="15" size="1">
				<option value="Not Set" <?php if($operatingSystem=="Not Set") echo ' selected="selected" ';?> >Not Set</option>
				<option value="OSX" <?php if($operatingSystem=="OSX") echo ' selected="selected" ';?> >OSX</option>
				<option value="Windows" <?php if($operatingSystem=="Windows") echo ' selected="selected" ';?> >Windows</option>
				<option value="Linux" <?php if($operatingSystem=="Linux") echo ' selected="selected" ';?> >Linux</option>
				<option value="Android" <?php if($operatingSystem=="Android") echo ' selected="selected" ';?> >Android</option>
				<option value="iOS" <?php if($operatingSystem=="iOS") echo ' selected="selected" ';?> >iOS</option>
				<option value="Windows Phone" <?php if($operatingSystem=="Windows Phone") echo ' selected="selected" ';?> >Windows Phone</option>
				<option value="BlackBerry" <?php if($operatingSystem=="BlackBerry") echo ' selected="selected" ';?> >Blackberry</option>
			</select>
		<br>
		<label for="lstProblemDueTo">Problem Due To:</label>
			<select id="lstProblemDueTo" name="lstProblemDueTo" tabindex="15" size="1">
				<option value="Not Set" <?php if($problemDueTo=="Not Set") echo ' selected="selected" ';?> >Not Set</option>
				<option value="Account" <?php if($problemDueTo=="Account") echo ' selected="selected" ';?> >Account</option>
				<option value="Hardware" <?php if($problemDueTo=="Hardware") echo ' selected="selected" ';?> >Hardware</option>
				<option value="Network" <?php if($problemDueTo=="Network") echo ' selected="selected" ';?> >Network</option>
				<option value="Software" <?php if($problemDueTo=="Software") echo ' selected="selected" ';?> >Software</option>
				<option value="Non-IT Question" <?php if($problemDueTo=="Non-IT Question") echo ' selected="selected" ';?> >Non-IT Question</option>
			</select>
		<label for="txtSpecificPointOfFailure" class="required">Specific Point of Failure *</label>
		   <input type="text" id="txtSpecificPointOfFailure" name="txtSpecificPointOfFailure" value="<?php echo $specificPointOfFailure; ?>" tabindex="1"
					size="25" maxlength="45" placeholder="Please enter specific point of failure" <?php if($specificPointOfFailureERROR) echo 'class="mistake"' ?>
					onfocus="this.select()" />	
		</fieldset>
		
		<!-- Creates Update text area for user input -->
		<!-- Must be in a single line for the placeholder tag to work -->
		<fieldset>
			<label for="txtUpdate">Short Description *</label>
				<br>
			<textarea placeholder="Update your ticket" rows="4" cols="50" id="txtUpdate" name="txtUpdate" maxlength="500"  <?php if($updateERROR) echo 'class="mistake"' ?>><?php echo $update; ?></textarea>
		</fieldset>
		
		<!-- Creates Submit and Reset Button for user input -->
		<section id="submit">
			<fieldset style="border: none;">               
				<input type="submit" id="btnSubmit" name="btnSubmit" value="Submit" 
					tabindex="34" class="button"/>
					
				<input type="reset" id="btnReset" name="btnReset" value="Reset Form" 
				tabindex="35" class="button" />
			</fieldset>    
		</section>
	</form>
   
			<?
			print "<p><b>Updates:<i> Most recent updates are shown first</i></b></p></p>";
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