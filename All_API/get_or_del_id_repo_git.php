<?php

header('Content-Type: application/json');

get_or_del_id_repo_git();

function get_or_del_id_repo_git()
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
            
            $id=$_GET['id'];
            
            if ($sth = $dbh->prepare("SELECT * FROM repositories WHERE github_id=".$id))
                {
                    $sth->execute();
                    $result = $sth->fetchAll(PDO::FETCH_OBJ);
                    echo json_encode($result);
                    echo "\n";
                }
            else
                {
                    echo json_encode( array("message" => "Repos NOT EXIST"));
                    echo "\n";
                }
        }
    elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE')
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
                }
            
            $id=$_GET['id'];
            
            if ($dbh->exec("DELETE FROM repositories WHERE github_id=".$id))
                {
                    echo json_encode( array("message" => "Repos DELETED"));
                    echo "\n";
                }
            else
                {
                    echo json_encode( array("message" => "Repos NOT EXIST"));
                    echo "\n";
                }
        }
}           

?>
