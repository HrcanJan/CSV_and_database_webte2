<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="./../fei.png">
    <link rel="stylesheet" href="../style.css">
    <title>REST</title>
</head>

<body>
<div class="grid">
    <div>
        <form id="search" class="marginbottom">
            <button type="submit">Get all data</button>
        </form>
        <form id="search_id" class="marginbottom">
            <label for="id">Inventor_id: </label>
            <input id="id" name="id" type="number" placeholder="search" value="28">
            <button type="submit" onsubmit="">Submit</button>
        </form>
        <form id="search_surname" class="marginbottom">
            <label for="surname">Inventor surname: </label>
            <input id="surname" name="surname" type="text" placeholder="search">
            <button type="submit" onsubmit="">Submit</button>
        </form>
        <form id="search_century" class="marginbottom">
            <label for="century">Century: </label>
            <input id="century" name="century" type="number" placeholder="search">
            <button type="submit" onsubmit="">Submit</button>
        </form>
        <form id="search_year" class="marginbottom">
            <label for="year">Year: </label>
            <input id="year" name="year" type="number" placeholder="search">
            <button type="submit" onsubmit="">Submit</button>
        </form>

        <hr>

        <h2>Add inventor</h2>
        <form id="upload" action="index.php" method="post" enctype="multipart/form-data">
            <div class="marginbottom">
                <label for="name">Inventor name: </label>
                <input id="name" name="name" type="text" placeholder="Inventor name">
            </div>
            <div class="marginbottom">
                <label for="surname">Inventor surname: </label>
                <input id="surname" name="surname" type="text" placeholder="Inventor surname">
            </div>
            <div class="marginbottom">
                <label for="description">Description: </label>
                <input id="description" name="description" type="text" placeholder="Description">
            </div>
            <div class="marginbottom">
                <label for="dob">Date of Birth: </label>
                <input id="dob" name="dob" type="date" placeholder="Date of Birth">
            </div>
            <div class="marginbottom">
                <label for="pob">Place of Birth: </label>
                <input id="pob" name="pob" type="text" placeholder="Place of Birth">
            </div>
            <div class="marginbottom">
                <label for="dod">Date of Death: </label>
                <input id="dod" name="dod" type="date" placeholder="Date of Death">
            </div>
            <div class="marginbottom">
                <label for="pod">Place of Death: </label>
                <input id="pod" name="pod" type="text" placeholder="Place of Death">
            </div>
            <div class="marginbottom">
                <label for="inv_date">Date of Invention: </label>
                <input id="inv_date" name="inv_date" type="number" placeholder="Date of Invention">
            </div>
            <div class="marginbottom">
                <label for="inv_d">Description of Invention: </label>
                <input id="inv_d" name="inv_d" type="text" placeholder="Description of Invention">
            </div>
            <input type="submit" value="Upload">
        </form>

        <h2>Add invention</h2>
        <form id="uploadi" action="index.php" method="post" enctype="multipart/form-data">
            <div class="marginbottom">
                <label for="id">Inventor id: </label>
                <input id="id" name="id" type="number">
            </div>
            <div class="marginbottom">
                <label for="inv_date">Invention date: </label>
                <input id="inv_date" name="inv_date" type="number" placeholder="Year">
            </div>
            <div class="marginbottom">
                <label for="description">Description: </label>
                <input id="description" name="description" type="text" placeholder="Description">
            </div>
            <input type="submit" value="Upload">

        </form>

        <h2>Delete inventor</h2>
        <form id="delete" action="index.php" method="delete">
            <div class="marginbottom">
                <label for="delete">Inventor id: </label>
                <input id="delete" name="delete" type="number" placeholder="Nefunguje">
            </div>
            <input type="submit" value="Delete">
        </form>

    </div>
    <div id="php">
        <?php
        require_once "../Inventor.php";
        require_once "../Invention.php";

        //header('Content-Type: application/json; charset=utf-8');

        switch ($_SERVER['REQUEST_METHOD']) {
            case "POST":
                header("HTTP/1.1 201 OK");
                $data = $_POST;
                if(isset($data['name']) && isset($data['surname']) && isset($data['description']) && isset($data['pob'])
                    && isset($data['dob']) && isset($data['inv_date']) && isset($data['inv_d'])) {
                    $inventor = new Inventor();
                    $inventor->setName($data['name']);
                    $inventor->setSurname($data['surname']);
                    $inventor->setDescription($data['description']);
                    $inventor->setBirthDateDate($data['dob']);
                    $inventor->setBirthPlace($data['pob']);
                    if($data['dod'] != "")
                        $inventor->setDeathDate($data['dod']);
                    if($data['pod'] != "")
                        $inventor->setDeathPlace($data['pod']);
                    $inventor->save();
                    $inventor_id = $inventor->getId();

                    $invention = new Invention();
                    $invention->setInventorId($inventor->getId());
                    $invention->setInventionDateDate($data['inv_date']);
                    $invention->setDescription($data['inv_d']);
                    $invention->save();
                } else if(isset($data['id']) && isset($data['inv_date']) && isset($data['description'])){
                    if(Inventor::find($data['id'])){
                        $invention = new Invention();
                        $invention->setInventorId($data['id']);
                        $invention->setInventionDateDate($data['inv_date']);
                        $invention->setDescription($data['description']);
                        $invention->save();
                    }
                }
                break;
            case "DELETE":
                $id = $_GET['delete'];
                if(Inventor::find($id)){
                    Inventor::find($id)->destroy();
                    header("HTTP/1.1 204 OK");
                }
                break;
            case "GET":
                header("HTTP/1.1 200 OK");

                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    if ($id == "")
                        echo json_encode(Inventor::all(), JSON_UNESCAPED_UNICODE);
                    else
                        echo json_encode(array(Inventor::find($id)->toArray(), Invention::findByInventorId($id)), JSON_UNESCAPED_UNICODE);

                } else if (isset($_GET['surname'])){
                    $surname = $_GET['surname'];
                    echo $surname;
                    if(!Inventor::searchBySurname($surname))
                        echo json_encode(Inventor::all(), JSON_UNESCAPED_UNICODE);
                    $id = Inventor::searchBySurname($surname)->toArray()['id'];
                    echo json_encode(array(Inventor::searchBySurname($surname)->toArray(), Invention::findByInventorId($id)), JSON_UNESCAPED_UNICODE);
                }else if(isset($_GET['year'])){
                    $year = $_GET['year'];
                    echo json_encode(Invention::findByYear($year), JSON_UNESCAPED_UNICODE);
                    if(Inventor::findByYear($year))
                        echo json_encode(Inventor::findByYear($year)->toArray(), JSON_UNESCAPED_UNICODE);
                }
                else if(isset($_GET['century'])){
                    $century = $_GET['century'];
                    echo json_encode(Invention::findByCentury($century), JSON_UNESCAPED_UNICODE);

                } else {
                    echo json_encode(Inventor::all(), JSON_UNESCAPED_UNICODE);
                }
                break;
        }
        ?>
    </div>
</div>

<script src="../script.js"></script>
</body>
</html>

