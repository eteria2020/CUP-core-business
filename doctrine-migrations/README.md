Before launching migration with doctrine you must manually create the new schema "business":

CREATE SCHEMA business AUTHORIZATION sharengo; GRANT ALL ON SCHEMA business TO sharengo;

to use doctrine migrations
login in host machine (or VM) as root
go in root folder of the project
run "vendor/bin/doctrine-module migrations:migrate" 