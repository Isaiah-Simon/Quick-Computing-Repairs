<?php

//This document allows for a user to input their data into the form. The form is then checked for error
//and is then sent to the database once error checking is completed and no errors found.


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// initialize my variables
//
$debug=true;

$firstName="";
$lastName="";
$emailAddress="";
$phoneNumber="";
$street="";
$city="";
$state="";
$zip="";
$shortDescription="";

$mailed = false;
$messageA = "";
$messageB = "";
$messageC = "";

$baseURL = "http://www.uvm.edu/~isimon/cs148/assignment7.1/";
$yourURL = $baseURL . "serviceform.php";

//initialize flags for errors, one for each item
$firstNameERROR = false;
$lastNameERROR = false;
$phoneNumberERROR = false;
$streetERROR = false;
$cityERROR = false;
$stateERROR = false;
$zipERROR = false;
$shortDescriptionERROR = false;
//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// if form has been submitted, validate the information
if (isset($_POST["btnSubmit"])){

    //************************************************************
    // is the refeering web page the one we want or is someone trying 
    // to hack in. this is not 100% reliable */
    $fromPage = getenv("http_referer"); 

    if ($debug) print "<p>From: " . $fromPage . " should match yourUrl: " . $yourURL . "<br> DEBUG MODE IS ON";
	
	require_once("connect.php");
	
    if($fromPage != $yourURL){
        die("<p>Sorry you cannot access this page. Security breach detected and reported</p>");
    } 
    
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
     // begin testing each form element 
     
     // Tests for empty and incorrect values in the text boxes
	 
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
	 
	// begin processing form data    
    if(!$errorMsg){    
        if ($debug) print "<p>Form is valid</p>";
    
   
	// Save the information
    // 
		$primaryKey = ""; 
        $dataEntered = false; 
		
		try { 
            $db->beginTransaction(); 
            
            //Saves Person information	
		$sql = 'INSERT INTO tblPerson SET ';
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
			
		$primaryKeyDevice = $db->lastInsertId();
		if ($debug) print "<p>pk= " . $primaryKeyDevice; 
		//Saves Person information	
		$sql = 'INSERT INTO tblService SET ';
	    $sql .= 'fldSubmittedBy="' . $firstName . ' ' . $lastName . '", ';
		$sql .= 'fldShortDescription="' . $shortDescription. '",';
		$sql .= 'fldStatus="Request"';
	
			$stmt = $db->prepare($sql); 
            if ($debug) print "<p>sql ". $sql; 
        
            $stmt->execute(); 
			
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
	
		
		// If the transaction was successful, give success message 
        if ($dataEntered) { 
            if ($debug) print "<p>data entered now prepare keys "; 
            
            //################################################################# 
            // 
            //Put forms information into a variable to print on the screen 
            // 

            $messageA = '<h2>Thank you for registering.</h2>'; 

            $messageB = "<p>Please wait for the next available technician</p>"; 

            $messageC .= "<h4>Fields equal to 1 are those that were checked. Blank fields are for fields that were left unselected.</h4>";
			$messageC .= "<p><b>First Name:</b><i>   " . $firstName . "</i></p>";
			$messageC .= "<p><b>Last Name:</b><i>   " . $lastName . "</i></p>";
			$messageC .= "<p><b>Email Address:</b><i>   " . $emailAddress . "</i></p>"; 
			$messageC .= "<p><b>Phone Number:</b><i>   " . $phoneNumber . "</i></p>"; 
			$messageC .= "<p><b>Street Address:</b><i>   " . $street . "</i></p>";
			$messageC .= "<p><b>City:</b><i>   " . $city . "</i></p>";
			$messageC .= "<p><b>State:</b><i>   " . $state . "</i></p>";
			$messageC .= "<p><b>Zip Code:</b><i>   " . $zip . "</i></p>";
			$messageC .= "<p><b>Short Description:</b><i>   " . $shortDescription . "</i></p>";
					
            //############################################################## 
            // 
            // email the form's information 
            // 
            
	$subject = "Quick Computing Repairs Receipt";
            include_once('mailMessage.php');
    $mailed = sendMail($emailAddress, $subject, $messageA . $messageB . $messageC); 
        } //data entered    
	
	//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
   
   }//End of Error Checking
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
            print "<h2>Your Request has "; 

            if (!$mailed) { 
                echo "not "; 
            } 

            echo "been processed</h2>"; 

            print '<p class="centered">A copy of this message has '; 
            if (!$mailed) { 
                echo "not "; 
            } 
            print "been sent to: " . $emailAddress . "</p>"; 

            echo $messageA . $messageC; 
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
	<h2>Service Form</h2>
	<form action="<? print $_SERVER['PHP_SELF']; ?>" method="post" id="frmRegister" enctype="multipart/form-data">
		<p><b>Required fields are marked in <span class="required"> red </span> and with an asterisks (*). </b><p>
		
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
		
		<!-- Creates Short Description text area for user input -->
		<!-- Must be in a single line for the placeholder tag to work -->
		<fieldset>
			<label for="txtDescription" class="required">Short Description *</label>
				<br>
			<textarea placeholder="Please enter a short description" rows="4" cols="50" id="txtShortDescription" name="txtShortDescription" maxlength="40"  <?php if($shortDescriptionERROR) echo 'class="mistake"' ?>><?php echo $shortDescription; ?></textarea>
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

<? include ("footer.php"); ?>

</section>

<?php } //ends form submitted ok ?>

</body>
</html>