<?php

check_release_repos();

function check_release_repos()
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
    
    $sth = $dbh->prepare("SELECT owner,name FROM repositories");
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($result as $new_result)
        {
            $usr=$new_result['owner'];
            $repos=$new_result['name'];
            
            
            $URL = 'https://api.github.com/repos/'.$usr.'/'.$repos.'/releases/latest';
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'api GITHUB');
            $resultats = curl_exec ($ch);
            $new_result= json_decode($resultats,true);
            curl_close($ch);
            
            if($new_result['message'] == "Not Found")
                {
                    echo json_encode( array("message" => "No result found."));
                    echo "\n";
                }
            else
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
                            echo 'Aucune connexion  : ' . $e->getMessage();
                        }
                    
                    $data = [];
                    
                    $data[] = [
			"github_id" => $github_id=$new_result['id'],
                        "owner" => $owner=$new_result['author']['login'],
                        "created_at" => $created_at=$new_result['created_at'],
                        "name" => $name=$new_result['name'],
                        "version" => $version=$new_result['tag_name'],
                    ];
                    
                    $new_date = date("Y-m-d", strtotime($created_at));
                }
            
            $stmt = $dbh->exec("INSERT INTO releases(owner,created_at,name,version,github_id) VALUES('$owner','$new_date','$name','$version','$github_id')");
            
            $error= $dbh->errorInfo();
            
            if(!$stmt)
                {
                    if($error[0] != 23000)
                        {
                            print_r($error);
                        }
                }
            else
                {
                    echo "<br>";
                    echo json_encode( array("$usr & $repos" => "New release added in DATABASE."));
                    echo "<br>";
                }
        }
}

?>
