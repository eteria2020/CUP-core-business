Before launching migration with doctrine you must manually create the new schema "business":

CREATE SCHEMA business AUTHORIZATION sharengo; GRANT ALL ON SCHEMA business TO sharengo;

to use doctrine migrations tools
login as root
go in root folder of project
run "vendor/bin/doctrine-module"