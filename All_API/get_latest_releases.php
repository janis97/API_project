<?php

header('Content-Type: application/json');

get_latest_releases();

function get_latest_releases()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $dsn = 'mysql:dbname=form;host=container_bdd';
            $user = 'janis';
            $password = 'root';
            
            try
                {
                    $dbh = new PDO($dsn, $user, $password);
                }
            catch (PDOException $e)
                {
                    echo 'Aucune connexion : ' . $e->getMessage();
                    die();
                }

            $sth = $dbh->prepare("SELECT owner,created_at,name,github_id,version FROM releases");
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($result);
            echo "\n";
        }
    else
        {
            echo json_encode( array("message" => "use GET as REQUEST_METHOD."));
            echo "\n";
        }
}           

?>
