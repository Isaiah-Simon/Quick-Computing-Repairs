<?php

//This document allows for a user to input their data into the form. The form is then checked for error
//and is then sent to the database once error checking is completed and no errors found.


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// initialize my variables
//
$debug=true;

$serviceNum="";
$submittedBy="";

$baseURL = "http://www.uvm.edu/~isimon/cs148/assignment7.1/";
$yourURL = $baseURL . "search.php";

//initialize flags for errors, one for each item
$serviceNumERROR = false;
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
	 
     $serviceNum=$_CLEAN['POST']['txtserviceNum'];
     if(empty($serviceNum)){
        $errorMsg[]="Please enter your Service Number";
        $serviceNumERROR = true;
     } else {
        $valid = verifyNum ($serviceNum); /* test for non-valid  data */
        if (!$valid){ 
            $errorMsg[]="First Name must be numbers only.";
            $serviceNumERROR = true;
        }
     }
	 
	// begin processing form data    
    if(!$errorMsg){    
        if ($debug) print "<p>Form is valid</p>";
    }
   
	// Retrieve the information
    // 
		$sql = "SELECT * FROM tblService WHERE pkserviceNum=" . $serviceNum; 
            $stmt = $db->prepare($sql); 
            $stmt->execute(); 

            $result = $stmt->fetch(PDO::FETCH_ASSOC); 
             
            $submittedBy = $result["fldSubmittedBy"];
			
			print $sql;
	
		
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
 <?  //############################################################################ 
 // 
 //  In this block  display the information that was submitted and do not  
 //  display the form. 
 // 
         if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { 
             print "<p><b>Submitted By:</b><i>   " . $submittedBy . "</i></p>";
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
        <label for="txtserviceNum" class="required">Service ID *</label>
        <input type="text" id="txtserviceNum" name="txtserviceNum" value="<?php echo $serviceNum; ?>" tabindex="1"
           size="25" maxlength="45" placeholder="Please enter your Service ID" <?php if($serviceNumERROR) echo 'class="mistake"' ?>
           onfocus="this.select()" />
           
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