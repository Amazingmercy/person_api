<?php
require_once("../db_config.php");


$response = array();
$row = array();

$method = $_SERVER["REQUEST_METHOD"];
$uri = $_SERVER["REQUEST_URI"];

//Collects uri and divides by the "/"
$parts = explode('/', $uri);
$input = isset($parts[2]) ? mysqli_real_escape_string($conn, $parts[2]) : null;


//To check if inputs are strings
function isValidString($value){
    return is_string($value) && strlen($value) > 0;
}

switch($method){
    case "GET":
        if($input){
            if(is_numeric($input)){
                $sql = "SELECT * FROM person WHERE id = ?;";
                $input_type = "i";
            }else{
                $sql = "SELECT * FROM person WHERE first_name = ? OR last_name = ?;";
                $input_type = "ss";
            }
        }else{
            $sql = "SELECT * FROM person;";
        }
        
        //To avoid SQL injections
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt) {
                if($input){
                    if ($input_type == "i") {
                        mysqli_stmt_bind_param($stmt, $input_type, $input);
                    }else{
                        mysqli_stmt_bind_param($stmt, $input_type, $input, $input);
                    }
                }
    
                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
    
                    if ($result){
                        $rows = array();
                        while ($row = mysqli_fetch_assoc($result)) {
                            $rows[] = $row;
                        }
                        if (!empty($rows)) {
                            $response["data"] = $rows;
                        } else {
                            $response["error"] = "User not found!";
                        }
                    }else{
                        $response["error"] = "Error fetching details.";
                    }
                } else {
                    $response["error"] = "Execution error: " . mysqli_stmt_error($stmt);
                }
                mysqli_stmt_close($stmt);
            }else{
                $response["error"] = "Error preparing SQL statement.";
            }
            break;        
        
        
    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $first_name = isset($data["first_name"]) && isValidString($data["first_name"])?mysqli_real_escape_string($conn, $data["first_name"]) : null;
        $last_name = isset($data["last_name"]) && isValidString($data["last_name"])?mysqli_real_escape_string($conn, $data["last_name"]) : null;
        $career = isset($data["career_path"]) && isValidString($data["career_path"])?mysqli_real_escape_string($conn, $data["career_path"]) : null;
        
        $sql = "INSERT INTO person (first_name, last_name, career_path) VALUES ('$first_name', '$last_name', '$career');";

        if(mysqli_query($conn, $sql)){
            $response["message"] = "User added sucessfully!";
        }else{
            $response["error"] = "Error adding user";
        }
        break;

    
    case "PUT":
            $data = json_decode(file_get_contents("php://input"), true);
            $first_name = isset($data["first_name"]) && isValidString($data["first_name"]) ? mysqli_real_escape_string($conn, $data["first_name"]) : null;
            $last_name = isset($data["last_name"]) && isValidString($data["last_name"]) ? mysqli_real_escape_string($conn, $data["last_name"]) : null;
            $career = isset($data["career_path"]) && isValidString($data["career_path"]) ? mysqli_real_escape_string($conn, $data["career_path"]) : null;
        

            if (is_numeric($input)) {
                $sql = "UPDATE person SET first_name = ?, last_name = ?, career_path = ? WHERE id = ?;";
                $input_type = "sssi";
            } else {
                $sql = "UPDATE person SET first_name = ?, last_name = ?, career_path = ? WHERE first_name = ? OR last_name = ?;";
                $input_type = "sssss";
            }
        
            $stmt = mysqli_prepare($conn, $sql);
        
            if ($stmt) {
                if ($input_type === "sssi") {
                    mysqli_stmt_bind_param($stmt, $input_type, $first_name, $last_name, $career, $input);
                } else {
                    mysqli_stmt_bind_param($stmt, $input_type, $first_name, $last_name, $career, $input, $input);
                }
        
                if (mysqli_stmt_execute($stmt)) {
                    $select_sql = is_numeric($input)
                        ? "SELECT * FROM person WHERE id = ?;"
                        : "SELECT * FROM person WHERE first_name = ? OR last_name = ?;";
                        
                    $stmt_select = mysqli_prepare($conn, $select_sql);
        
                    if ($stmt_select) {
                        if ($input_type === "sssi") {
                            mysqli_stmt_bind_param($stmt_select, "s", $input);
                        } else {
                            mysqli_stmt_bind_param($stmt_select, $input_type, $input, $input);
                        }
        
                        if (mysqli_stmt_execute($stmt_select)) {
                            $result_select = mysqli_stmt_get_result($stmt_select);
        
                            if ($result_select) {
                                $row = mysqli_fetch_assoc($result_select);
                                $response["data"] = $row;
                                $response["message"] = "User details updated successfully!";
                            } else {
                                $response["error"] = "Failed to fetch updated details";
                            }
                        } else {
                            $response["error"] = "Execution error: " . mysqli_stmt_error($stmt_select);
                        }
                        mysqli_stmt_close($stmt_select);
                    } else {
                        $response["error"] = "Error preparing SELECT SQL statement.";
                    }
                } else {
                    $response["error"] = "Execution error: " . mysqli_stmt_error($stmt);
                }
                mysqli_stmt_close($stmt);
            } else {
                $response["error"] = "Error preparing UPDATE SQL statement.";
            }
            break;
        
        
    
    case "DELETE":
        if (is_numeric($input)) {
            $sql = "DELETE FROM person WHERE id = ?;";
            $input_type = "i"; 
        } else {
        $sql = "DELETE FROM person WHERE first_name = ? OR last_name = ?;";
        $input_type = "ss"; 
        }

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            if ($paramType === "i") {
                mysqli_stmt_bind_param($stmt, $paramType, $input);
            } else {
                mysqli_stmt_bind_param($stmt, $paramType, $input, $input);
            }

            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_affected_rows($conn) > 0) {
                    $response["message"] = "User deleted successfully!";
                } else {
                    $response["error"] = "User not found!";
                }
            } else {
                $response["error"] = "Execution error: " . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt); 
        } else {
            $response["error"] = "Error preparing DELETE SQL statement.";
        }
        break;


    default:
        $response["error"] = "Unsupported HTTP method.";
        break;
}


if (isset($response["error"])) {
    http_response_code(400);
} else {
    http_response_code(200);
}


header("Content-Type: application/json");
echo json_encode($response, JSON_PRETTY_PRINT);

mysqli_close($conn);



?>