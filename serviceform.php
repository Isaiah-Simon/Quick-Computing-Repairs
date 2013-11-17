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
			<textarea placeholder="Please enter a short description" rows="4" cols="50" id="txtDescription" name="txtDescription" maxlength="500"  <?php if($descriptionERROR) echo 'class="mistake"' ?>><?php echo $description; ?></textarea>
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