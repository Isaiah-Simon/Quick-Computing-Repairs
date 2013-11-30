<?php

//This document allows for a user to input their data into the form. The form is then checked for error
//and is then sent to the database once error checking is completed and no errors found.


//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
// initialize my variables
//
$debug=false;

$serviceNum="";
$submittedBy="";
$status="";
$statuses="";
$serviceNumSearches="";
$serviceNumSearch="";
$searchBy="";

$baseURL = "http://www.uvm.edu/~isimon/cs148/assignment7.1/";
$yourURL = $baseURL . "adminSearch.php";

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
	 
	 //Sets list value for items to create sticky list
	if(isset($_CLEAN['POST']["lstStatus"])){
            $status = $_CLEAN['POST']["lstStatus"];
    }
	
	if(isset($_CLEAN['POST']["lstSearchBy"])){
            $searchBy = $_CLEAN['POST']["lstSearchBy"];
    }
         
        // begin processing form data    
    if(!$errorMsg){    
        if ($debug) print "<p>Form is valid</p>";
    }
   
        // Retrieve the information
    // 
                $sql = "SELECT pkServiceNum, fldSubmittedBy, fldStatus, fldDateCreated FROM tblService WHERE pkserviceNum=" . $serviceNum; 
            $stmt = $db->prepare($sql); 
            $stmt->execute(); 
			$serviceNumSearches = $stmt->fetchAll(); 
             
            $submittedBy = $result["fldSubmittedBy"];
			
			print "submited = " . $submittedBy;
              
                
                        
                $sql = "SELECT pkServiceNum, fldSubmittedBy, fldStatus, fldDateCreated FROM tblService WHERE fldStatus=" . '"' . $status . '" ORDER BY pkServiceNum ASC'; 
            $stmt = $db->prepare($sql); 
            $stmt->execute(); 
			$statuses = $stmt->fetchAll(); 
			
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
            if ($searchBy=="status") { 
                        print "<table>\n";
                        print "<tr>\n";
                        print "</tr>\n";
						print "<td>Service Number</td>";
						print "<td>Submitted By</td>";
						print "<td>Status</td>";
						print "<td>Date Created</td>";
                        // -----------------------------------------------------------------------------
                        // print out the records
                        foreach ($statuses as $status) {
                                print "<tr>\n";
								print "<td><b>" . $status['pkServiceNum'] . "</b></td>\n";
                                print "<td><b>" . $status['fldSubmittedBy'] . "</b></td>\n";
                                print "<td><i>" . $status['fldStatus'] . "</i></td>\n";
								print "<td><i>" . $status['fldDateCreated'] . "</i></td>\n";
								print "<td><i><a href='http://www.uvm.edu/~isimon/cs148/assignment7.1/adminView.php?q=" . $status['pkServiceNum'] . "'>View</a></i></td>\n";
								print "<td><i><a href='http://www.uvm.edu/~isimon/cs148/assignment7.1/adminEdit.php?q=" . $status['pkServiceNum'] . "'>Edit</a></i></td>\n";
                                print "</tr>\n";
                        }
                        print "</table>\n";
                        } else if ($searchBy=="serviceNum") { 
							if ($serviceNumSearches != null) {
                            print "<table>\n";
							print "<tr>\n";
							print "</tr>\n";
							print "<td>Service Number</td>";
							print "<td>Submitted By</td>";
							print "<td>Status</td>";
							print "<td>Date Created</td>";
							// -----------------------------------------------------------------------------
							// print out the records
							foreach ($serviceNumSearches as $serviceNumSearch) {
									print "<tr>\n";
									print "<td><b>" . $serviceNumSearch['pkServiceNum'] . "</b></td>\n";
									print "<td><b>" . $serviceNumSearch['fldSubmittedBy'] . "</b></td>\n";
									print "<td><i>" . $serviceNumSearch['fldStatus'] . "</i></td>\n";
									print "<td><i>" . $serviceNumSearch['fldDateCreated'] . "</i></td>\n";
									print "<td><i><a href='http://www.uvm.edu/~isimon/cs148/assignment7.1/adminView.php?q=" . $serviceNumSearch['pkServiceNum'] . "'>View</a></i></td>\n";
									print "<td><i><a href='http://www.uvm.edu/~isimon/cs148/assignment7.1/adminEdit.php?q=" . $serviceNumSearch['pkServiceNum'] . "'>Edit</a></i></td>\n";
									print "</tr>\n";
									}
							} else {
								print "This record does not exist, please try a different service ID Number";
							}
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
   <h2>Administrative Search</h2>
   <form action="<? print $_SERVER['PHP_SELF']; ?>" method="post" id="frmRegister" enctype="multipart/form-data">
     <p><b>You may search by either the client's service ID number or by the status of the ticket. </b><p>
     
     <!-- Creates First Name text box for user input -->
     <fieldset>               
        <label for="lstSearchBy">Search By:</label>
			<select id="lstSearchBy" name="lstSearchBy" tabindex="15" size="1">
				<option value="serviceNum" <?php if($searchBy=="serviceNum") echo ' selected="selected" ';?> >Service Number</option>
				<option value="status" <?php if($searchBy=="status") echo ' selected="selected" ';?> >Status</option>
			</select>
	</fieldset> 	 
	<fieldset> 	
		<label for="txtserviceNum" class="required">Service ID *</label>
        <input type="text" id="txtserviceNum" name="txtserviceNum" value="<?php echo $serviceNum; ?>" tabindex="1"
           size="30" maxlength="45" placeholder="Please enter a Service ID number" <?php if($serviceNumERROR) echo 'class="mistake"' ?>
           onfocus="this.select()" />
		
		<label for="lstStatus">Status:</label>
			<select id="lstStatus" name="lstStatus" tabindex="15" size="1">
				<option value="request" <?php if($status=="Request") echo ' selected="selected" ';?> >Request</option>
				<option value="closed" <?php if($status=="Closed") echo ' selected="selected" ';?> >Closed</option>
				<option value="assisting" <?php if($status=="Assisting") echo ' selected="selected" ';?> >Assisting</option>
				<option value="awaitingClientPickUp" <?php if($status=="AwaitingClientPickUp") echo ' selected="selected" ';?> >Awaiting Client Pickup</option>
				<option value="awaitingClientAction" <?php if($status=="AwaitingClientAction") echo ' selected="selected" ';?> >Awaiting Client Action</option>
			</select>
     </fieldset>       
     <!-- Creates Submit and Reset Button for user input -->
     <section id="submit">
	 <fieldset> 
       <fieldset style="border: none;">               
         <input type="submit" id="btnSubmit" name="btnSubmit" value="Submit" 
           tabindex="34" class="button"/>
       </fieldset>    
     </section>
   </form>
 </section>
 
 <? include ("footer.php"); ?>
 
 </section>
 
 <?php } //ends form submitted ok ?>
 </body>
 </html>