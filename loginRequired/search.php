<?php

//This document allows for a user to input their data into the form. The form is then checked for error
//and is then sent to the database once error checking is completed and no errors found.


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// initialize my variables
//
$debug=false;

$serviceNum="";
$submittedBy="";
$shortDescription="";
$status="";
$updates="";
$update="";
$updateTime="";

$baseURL = "https://www.uvm.edu/~isimon/cs148/assignment7.1";
$yourURL = $baseURL . "/loginRequired/search.php";

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
	$serviceNum = htmlentities($_POST["txtServiceNum"], ENT_QUOTES, "UTF-8");
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
            $errorMsg[]="Service Number must be numbers only.";
            $serviceNumERROR = true;
        }
     }
	 
	// begin processing form data    
    if(!$errorMsg){    
        if ($debug) print "<p>Form is valid</p>";
    }
   
	// Retrieve the information
    // 
		$sql = "SELECT fldSubmittedBy,fldStatus,fldShortDescription FROM tblService WHERE pkserviceNum=" . $serviceNum; 
            $stmt = $db->prepare($sql); 
            $stmt->execute(); 

            $result = $stmt->fetch(PDO::FETCH_ASSOC); 
             
            $submittedBy = $result["fldSubmittedBy"];
			$status = $result["fldStatus"];
			$shortDescription = $result["fldShortDescription"];
			
		
			
		$sql = "SELECT fldUpdateText,fldTimeEdited FROM tblUpdate WHERE fkserviceNum=" . $serviceNum . "  ORDER BY fldTimeEdited desc";
            $stmt = $db->prepare($sql); 
            $stmt->execute(); 
			$updates = $stmt->fetchAll();
	
		
		// If the transaction was successful, give success message 
	//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
	
}
?>

<? include ("top.php"); ?>
 
 <body id="search">
 
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
            if (!empty($submittedBy)) { 
			print "<p><b>Short Description:</b><i>   " . $shortDescription . "</i></p>";
			print "<p><b>Submitted By:</b><i>   " . $submittedBy . "</i></p>";
			print "<p><b>Status:</b><i>   " . $status . "</i></p>";
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
			} else { 
				print "That record does not exist, please input a valid service number";
			}
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
   <h2>Search</h2>
   <form action="<? print $_SERVER['PHP_SELF']; ?>" method="post" id="frmRegister" enctype="multipart/form-data">
		<p>Below you will be able to search by your Service ID Number that was emailed to you when you submitted your initial service ticket request. Once you input your Service ID Number, you will be able to view the short description, status and updates to your service ticket.</p>
		<p><b>Required fields are marked in <span class="required"> red </span> and with an asterisks (*). </b><p>
     
     <!-- Creates First Name text box for user input -->
     <fieldset>               
        <label for="txtserviceNum" class="required">Service ID *</label>
        <input type="text" id="txtserviceNum" name="txtserviceNum" value="<?php echo $serviceNum; ?>" tabindex="1"
           size="25" maxlength="45" placeholder="Please enter your Service ID" <?php if($serviceNumERROR) echo 'class="mistake"' ?>
           onfocus="this.select()" />
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
 </html>
