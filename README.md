RESTFUL API GITHUB	 
==================

#### INTRODUCTION
In this project, we want to receive instantaly a notification when a repository github (that we will choose to add in our database) has a new release.

#### INSTALLATION
So if you need to use this project, you must to follow these recommendations:

 - Create a new image which will contain all API that we will need to call ``` docker build -f Dockerfile_API -t name_of_your_image . ```
 - Create a second image for your database which will contain all informations that we need to stock ``` docker build -f Dockerfile_MYSQL -t name_of_your_image . ```
 - And create a last image for the script which will allow to have instantaly a notification when a repository in the database has a new release ``` docker build -f Dockerfile_SCRIPT-RELEASES -t name_of_your_image . ```

After that, in order to create the 3 containers base on our images ``` docker-compose up -d ```

#### USE
Now everything is ready to recover the repositories that you want and check their new releases, you just have to call these API:

 - Add a new repository in the database ``` curl -i -H "Content-Type: application/json" -X POST -d '{"user":"name_of_user","repos":"name_of_repos"}' http://localhost:8082/repo ```
 - Display all repositories in the database ``` curl -i -H "Content-Type: application/json" -X GET http://localhost:8082/repos ```
 - Display just one repository in the database ``` curl -i -H "Content-Type: application/json" -X GET http://localhost:8082/repo?id=::github_id:: ```
 - Delete a repositoy in the database ``` curl -i -H "Content-Type: application/json" -X DELETE http://localhost:8082/repo?id=::github_id:: ```
 - Display all releases of repositories in the database ``` curl -i -H "Content-Type: application/json" -X GET http://localhost:8082/releases ```

So let's go and have fun :clap: :smiley:
