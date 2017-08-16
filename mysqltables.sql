CREATE TABLE repositories(github_id INT(11) NOT NULL, owner VARCHAR(150) NOT NULL, created_at date NOT NULL, name VARCHAR(150) NOT NULL);

CREATE TABLE releases(github_id INT(11) NOT NULL, owner VARCHAR(150) NOT NULL, created_at date NOT NULL, version varchar(150) NOT NULL, name VARCHAR(150) NOT NULL);

ALTER TABLE `repositories` ADD UNIQUE(`github_id`);

ALTER TABLE `releases` ADD UNIQUE(`github_id`);
