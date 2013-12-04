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
$updateText="";

//initialize flags for errors, one for each item
$shortDescriptionERROR = false;
$firstNameERROR = false;
$lastNameERROR = false;
$phoneNumberERROR = false;
$emailAddressERROR = false;
$streetERROR = false;
$cityERROR = false;
$stateERROR = false;
$zipERROR = false;
$specificPointOfFailureERROR= false;
$updateTextERROR= false;

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// if form has been submitted, validate the information
if (isset($_POST["btnSubmit"])){

   // ************************************************************
    // is the refeering web page the one we want or is someone trying 
    // to hack in. this is not 100% reliable */
    $fromPage = getenv("http_referer"); 
	$baseURL = "http://www.uvm.edu/~isimon/cs148/assignment7.1/";
	$yourURL = $baseURL . "adminEdit.php?q=" . $serviceNum;
	
    if ($debug) print "<p>From: " . $fromPage . " should match yourUrl: " . $yourURL . "<br> DEBUG MODE IS ON";
	
	require_once("connect.php");
    
	/*Replace any html or javascript code with html entities*/
	$firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
	$lastName = htmlentities($_POST["txtlastName"], ENT_QUOTES, "UTF-8");
	$emailAddress = htmlentities($_POST["txtEmail"], ENT_QUOTES, "UTF-8");
	$phoneNumber = htmlentities($_POST["txtPhone"], ENT_QUOTES, "UTF-8");
	$street = htmlentities($_POST["txtStreet"], ENT_QUOTES, "UTF-8");
	$city = htmlentities($_POST["txtCity"], ENT_QUOTES, "UTF-8");
	$state = htmlentities($_POST["txtState"], ENT_QUOTES, "UTF-8");
	$zip = htmlentities($_POST["txtZip"], ENT_QUOTES, "UTF-8");
	$shortDescription = htmlentities($_POST["txtShortDescription"], ENT_QUOTES, "UTF-8");
	$updateText = htmlentities($_POST["txtUpdate"], ENT_QUOTES, "UTF-8");
	$specificPointOfFailure = htmlentities($_POST["txtSpecificPointOfFailure"], ENT_QUOTES, "UTF-8");
	
    /*
        this function just converts all input to html entities to remove any potentially
        malicious coding
    */
    function clean($elem)
    {
        if(!is_array($elem))
            $elem = htmlentities($elem,ENT_QUOTES,"UTF-8");
        else
            foreach ($elem as $key => $value)
                $elem[$key] = clean($value);
        return $elem;
     }

   // be sure to clean out any code that was submitted
     if(isset($_GET)) $_CLEAN['GET'] = clean($_GET);
     if(isset($_POST)) $_CLEAN['POST'] = clean($_POST); 
	  
	  //check for errors
     include ("validation_functions.php");
     $errorMsg=array();
	 
	 //%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^
    
     //%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
     //begin testing each form element 
     
     //Tests for empty and incorrect values in the text boxes
	$firstName=$_CLEAN['POST']['txtFname'];
     if(empty($firstName)){
        $errorMsg[]="Please enter your First Name";
        $firstNameERROR = true;
     } else {
        $valid = verifyAlphaNum ($firstName); /* test for non-valid  data */
        if (!$valid){ 
            $errorMsg[]="First Name must be letters and numbers, spaces, dashes and single quotes only.";
            $firstNameERROR = true;
        }
     }
	 
    $lastName=$_CLEAN['POST']['txtLname'];
     if(empty($lastName)){
        $errorMsg[]="Please enter your Last Name";
        $lastNameERROR = true;
     } else {
        $valid = verifyAlphaNum ($lastName); /* test for non-valid  data */
        if (!$valid){ 
            $errorMsg[]="Last Name must be letters and numbers, spaces, dashes and single quotes only.";
            $lastNameERROR = true;
        }
     }
	 
	 $emailAddress=$_CLEAN['POST']['txtEmail'];
     if(empty($emailAddress)){
        $errorMsg[]="Please enter your Email address";
        $emailAddressERROR = true;
     } else {
        $valid = verifyEmail ($emailAddress); /* test for non-valid  data */
        if (!$valid){ 
            $errorMsg[]="Please enter a valid Email address";
            $emailAddressERROR = true;
        }
     }
	 
	 $phoneNumber=$_CLEAN['POST']['txtPhone'];
     if(empty($phoneNumber)){
        $errorMsg[]="Please enter your Phone Number";
        $phoneNumberERROR = true;
     } else {
        $valid = verifyNum ($phoneNumber); /* test for non-valid  data */
        if (!$valid){ 
            $errorMsg[]="Please enter a valid Email address";
            $phoneNumberERROR = true;
        }
     }
	 
	$street=$_CLEAN['POST']['txtStreet'];
     if(empty($street)){
        $errorMsg[]="Please enter your Street Address";
        $streetERROR = true;
     }
	 
	$city=$_CLEAN['POST']['txtCity'];
     if(empty($city)){
        $errorMsg[]="Please enter your City";
        $cityERROR = true;
     } else {
        $valid = verifyAlphaNum ($city); /* test for non-valid  data */
        if (!$valid){ 
            $errorMsg[]="Please enter a valid Street Address";
            $cityERROR = true;
        }
     }
	 
	 $state=$_CLEAN['POST']['txtState'];
     if(empty($state)){
        $errorMsg[]="Please enter your State";
        $stateERROR = true;
     } else {
        $valid = verifyAlphaNum ($state); /* test for non-valid  data */
        if (!$valid){ 
            $errorMsg[]="Please enter a valid State";
            $stateERROR = true;
        }
     }
	 
	 $zip=$_CLEAN['POST']['txtZip'];
     if(empty($zip)){
        $errorMsg[]="Please enter your Zip Code";
        $zipERROR = true;
     } else {
        $valid = verifyNum ($zip); /* test for non-valid  data */
        if (!$valid){ 
            $errorMsg[]="Please enter a valid Zip Code";
            $zipERROR = true;
        }
     }
	 
	 $shortDecription=$_CLEAN['POST']['txtShortDescription'];
     if(empty($shortDescription)){
        $errorMsg[]="Please enter a Short Description";
        $shortDescriptionERROR = true;
     } else {
        $valid = verifyText ($shortDescription); /* test for non-valid  data */
        if (!$valid){ 
            $errorMsg[]="Short Description must be letters and numbers, spaces, dashes and single quotes only.";
            $shortDescriptionERROR = true;
        }
     }

	$specificPointOfFailure=$_CLEAN['POST']['txtSpecificPointOfFailure'];
     if(empty($specificPointOfFailure)){
        $errorMsg[]="Please enter a Specific Point of Failure";
        $specificPointOfFailureERROR = true;
     } else {
        $valid = verifyText ($specificPointOfFailure); /* test for non-valid  data */
        if (!$valid){ 
            $errorMsg[]="Short Description must be letters and numbers, spaces, dashes and single quotes only.";
            $specificPointOfFailure = true;
        }
     }
	 
	 $updateText=$_CLEAN['POST']['txtUpdateText'];
     if (!$valid){ 
            $errorMsg[]="Short Description must be letters and numbers, spaces, dashes and single quotes only.";
            $shortDescriptionERROR = true;
	}

	if(!$errorMsg){    
        if ($debug) print "<p>Form is valid</p>";
	}
	
	//Sets list value for items to create sticky list
	if(isset($_CLEAN['POST']["lstStatus"])){
            $status = $_CLEAN['POST']["lstStatus"];
    }
	
	if(isset($_CLEAN['POST']["lstPriority"])){
            $priority = $_CLEAN['POST']["lstPriority"];
    }
	
	//Sets list value for items to create sticky list
	if(isset($_CLEAN['POST']["lstPlatform"])){
            $platform = $_CLEAN['POST']["lstPlatform"];
    }
	
	//Sets list value for items to create sticky list
	if(isset($_CLEAN['POST']["lstOperatingSystem"])){
            $operatingSystem = $_CLEAN['POST']["lstOperatingSystem"];
    }
	
	//Sets list value for items to create sticky list
	if(isset($_CLEAN['POST']["lstProblemDueTo"])){
            $problemDueTo = $_CLEAN['POST']["lstProblemDueTo"];
    }
	
	//SQL commands
	try { 
            $db->beginTransaction(); 
            
            //Saves Person information	
		$sql = 'INSERT tblPerson SET ';
	    $sql .= 'pkEmailAddress="' . $emailAddress . '", ';
	    $sql .= 'fldFirstName="' . $firstName . '", ';
		$sql .= 'fldLastName="' . $lastName . '", ';
		$sql .= 'fldPhoneNumber=' . $phoneNumber . ', ';
		$sql .= 'fldStreet="' . $street . '", ';
		$sql .= 'fldCity="' . $city . '", ';
		$sql .= 'fldState="' . $state . '", ';
		$sql .= 'fldZip=' . $zip ;
           
			$stmt = $db->prepare($sql); 
            if ($debug) print "<p>sql ". $sql; 
        
            $stmt->execute(); 
			
		//Saves Service information	
		$sql = 'INSERT tblService SET ';
		$sql .= 'fkEmailAddress="' . $emailAddress. '",';
	    $sql .= 'fldSubmittedBy="' . $firstName . ' ' . $lastName . '",';
		$sql .= 'fldStatus="' . $status. '",';
		$sql .= 'fldPriority="' . $priority. '",';
		$sql .= 'fldPlatform="' . $platform. '",';
		$sql .= 'fldOperatingSystem="' . $operatingSystem. '",';
		$sql .= 'fldProblemDueTo="' . $problemDueTo. '",';
		$sql .= 'fldSpecificPointOfFailure="' . $specificPointOfFailure. '",';
		$sql .= 'fldShortDescription="' . $shortDescription. '"';
	
			$stmt = $db->prepare($sql); 
            if ($debug) print "<p>sql ". $sql; 
        
            $stmt->execute(); 
		// Saves update information	
		$serviceNum = $db->lastInsertId();
		if (!empty($updateText)){
		$sql = 'INSERT INTO tblUpdate SET ';
		$sql .= 'fkServiceNum="' . $serviceNum. '",';
		$sql .= 'fldUpdateText="' . $updateText. '"';
		
			$stmt = $db->prepare($sql); 
            if ($debug) print "<p>sql ". $sql; 
        
            $stmt->execute();
		}
			
		$sql = "UPDATE tblPerson SET fldzip = LPAD(fldzip, 5, '0')";
		
			$stmt = $db->prepare($sql); 
            if ($debug) print "<p>sql ". $sql; 
        
            $stmt->execute();
			
			// all sql statements are done so lets commit to our changes 
            $dataEntered = $db->commit(); 
            if ($debug) print "<p>transaction complete "; 
        } catch (PDOExecption $e) { 
            $db->rollback(); 
            if ($debug) print "Error!: " . $e->getMessage() . "</br>"; 
            $errorMsg[] = "There was a problem with accpeting your data please contact us directly."; 
        } 
}
?>

<? include ("top.php"); ?>
 
 <body id="form">
 
 <section id="bgColor" class="rounded-corners border">
 <? include ("header.php"); ?>
 <? include ("nav.php"); ?>
 
 <section id="content">  
 <?	//############################################################################ 
// 
//  In this block  display the information that was submitted and do not  
//  display the form. 
// 
        if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { 
            print "<h2>The record has been added."; 
			print '<p><a href="http://www.uvm.edu/~isimon/cs148/assignment7.1/admin.php">Return to Admin Page</a></p>';
        } else {
?>

	<section id="errors">
			<?php
			if($errorMsg){
				echo "<ol>\n";
				foreach($errorMsg as $err){
					echo "<li>" . $err . "</li>\n";
				}
				echo "</ol>\n";
			} 
			?>
	</section>
	 
	 
	 <section>
	   <h2>Admin Edit</h2>
			<form action="<? print $_SERVER['PHP_SELF']; ?>" method="post" id="frmAdminEdit" enctype="multipart/form-data">
			<p><b>Required fields are marked with an asterisks (*). </b><p>
			<fieldset>
				<label for="txtShortDescription" class="required">Short Description *</label>
				<input type="text" id="txtShortDescription" name="txtShortDescription" value="<?php echo $shortDescription; ?>" tabindex="1"
						size="75" maxlength="75" placeholder="Enter a Short Description" <?php if($shortDescriptionERROR) echo 'class="mistake"' ?>
						onfocus="this.select()" />
				<br />
				<label for="lstStatus">Status:</label>
				<select id="lstStatus" name="lstStatus" tabindex="15" size="1">
					<option value="Request" <?php if($status=="Request") echo ' selected="selected" ';?> >Request</option>
					<option value="Closed" <?php if($status=="Closed") echo ' selected="selected" ';?> >Closed</option>
					<option value="Assisting" <?php if($status=="Assisting") echo ' selected="selected" ';?> >Assisting</option>
					<option value="Awaiting Client PickUp" <?php if($status=="AwaitingClientPickUp") echo ' selected="selected" ';?> >Awaiting Client Pickup</option>
					<option value="Awaiting Client Action" <?php if($status=="AwaitingClientAction") echo ' selected="selected" ';?> >Awaiting Client Action</option>
				</select>
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
						size="35" maxlength="45" placeholder="Please enter specific point of failure" <?php if($specificPointOfFailureERROR) echo 'class="mistake"' ?>
						onfocus="this.select()" />	
			</fieldset>
			
			<!-- Creates Update text area for user input -->
			<!-- Must be in a single line for the placeholder tag to work -->
			<fieldset>
				<label for="txtUpdateText">Update *</label>
					<br>
				<textarea placeholder="Update your ticket" rows="4" cols="50" id="txtUpdateText" name="txtUpdateText" maxlength="500"  <?php if($updateTextERROR) echo 'class="mistake"' ?>><?php echo $updateText; ?></textarea>
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
	 </section>
 </section>
 
 <? include ("footer.php"); ?>
 
 </section>
 <?php } //ends form submitted ok ?>
 
 </html>