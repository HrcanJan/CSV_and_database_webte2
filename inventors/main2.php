<?php
require_once "../Inventor.php";
require_once "../Invention.php";

//header('Content-Type: application/json; charset=utf-8');

switch ($_SERVER['REQUEST_METHOD']) {
    case "GET":
        header("HTTP/1.1 200 OK");

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if ($id == "")
                break;
            else
                echo json_encode(array(Invention::findByInventorId($id)), JSON_UNESCAPED_UNICODE);

        } else if (isset($_GET['surname'])){
            $surname = $_GET['surname'];
            if(!Inventor::searchBySurname($surname))
                break;
            $id = Inventor::searchBySurname($surname)->toArray()['id'];
            echo json_encode(array(Invention::findByInventorId($id)), JSON_UNESCAPED_UNICODE);
        }else if(isset($_GET['year'])){
            $year = $_GET['year'];
            echo json_encode(Invention::findByYear($year), JSON_UNESCAPED_UNICODE);
        }
        else if(isset($_GET['century'])){
            $century = $_GET['century'];
            echo json_encode(Invention::findByCentury($century), JSON_UNESCAPED_UNICODE);

        } else {
            echo json_encode(Invention::all(), JSON_UNESCAPED_UNICODE);
        }
        break;
}