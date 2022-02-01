<?php

    #Retrieve data from json sent by front end 
	$inData = getRequestInfo();
	
    #Collect info about contact to be deleted from user
	$deleteFirstName = $inData["deleteFirstName"];
    $deleteLastName = $inData["deleteLastName"];
    $deleteEmail = $inData["deleteEmail"];
	$userId = $inData["userId"];

    #Connect to database
	$conn = new mysqli("localhost", "User2", "StayProtected", "COP4331");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
        #Delete Contact information from database. Delete keys are case-sensitive
		$stmt = $conn->prepare("DELETE FROM Contacts WHERE (FirstName = ? AND LastName = ? AND email = ?) AND ID = ?");
		$stmt->bind_param("sssi", $deleteFirstName, $deleteLastName, $deleteEmail, $userId);
		$stmt->execute();
		$stmt->close();
		$conn->close();
		returnWithError("");
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
?>
