<?php
function verifyAlphaNum ($testString) {
    // Check for letters, numbers and dash, period, space and single quote only. 
    return (preg_match ("/^[a-zA-Z -]+$/", $testString));
}     

function verifyEmail ($testString) {
    // Check for a valid email address 
    return (preg_match("/^([[:alnum:]]|_|\.|-)+@([[:alnum:]]|\.|-)+(\.)([a-z]{2,4})$/", $testString));
}

function verifyText ($testString) {
    // Check for letters, numbers and dash, period, ?, !, space and single and double quotes only. 
    return (preg_match("/^([[:alnum:]]|-|\.| |\n|\r|\?|\!|\"|\')+$/",$testString));
}

function verifyNum ($testString) {
    // Check for only numbers, dashes and spaces in the phone number 
    return (preg_match('/^([[:digit:]])+$/', $testString));
}

function verifyZip ($testString) {
    // Check for only numbers, dashes and spaces in the phone number 
    return (preg_match('/^([[:digit:]]| |-)+$/', $testString));
}

function verifyCard ($testString) {
    // Check for only numbers, dashes and spaces in the card number 
    return (preg_match('/^([[:digit:]]| |-)+$/', $testString));
}
?>