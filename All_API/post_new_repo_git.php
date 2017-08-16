<?php

header('Content-Type: application/json');

post_repos_git();

function post_repos_git()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $inputJSON = file_get_contents('php://input');
            
            $input= json_decode( $inputJSON,true );
            
            $user=$input['user'];
            $repos=$input['repos'];
            
            $URL = 'https://api.github.com/repos/'.$user.'/'.$repos.'';
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'api GITHUB');
            $resultats = curl_exec ($ch);
            $new_result= json_decode($resultats,true); 
            curl_close($ch);
            
            if($new_result['message'] == "Not Found")
                {
                    echo json_encode( array("message" => "No Repos found."));
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
                            echo 'Aucune connexion : ' . $e->getMessage();
                            die();
                        }
                    $data = [];
                    
                    $data[] = [
                        "owner" => $owner=$new_result['owner']['login'],
                        "created_at" => $created_at=$new_result['created_at'],
                        "name" => $name=$new_result['name'],
                        "github_id" => $github_id=$new_result['id'],
                    ];
                    
                    $new_date = date("Y-m-d", strtotime($created_at));
                }
       
            $stmt = $dbh->exec("INSERT INTO repositories(owner,created_at,name,github_id) VALUES('$owner','$new_date','$name','$github_id')");

            $error= $dbh->errorInfo();
            
            if(!$stmt)
                {
                     if($error[0] == 23000)
                         {
                             echo json_encode( array("message" => "Already added in DATABASE."));
                             echo "\n";
                         }
                     else
                         {
                             print_r($error);
                         }
                }
            else
                {
                      echo json_encode( array("message" => "Added in DATABASE."));
                      echo "\n";
                }
            
        }
    else
        {
            echo json_encode( array("message" => "use POST as REQUEST_METHOD."));
            echo "\n";
        }
}

?>
