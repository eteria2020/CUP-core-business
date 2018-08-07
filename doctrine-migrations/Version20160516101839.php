<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160516101839 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("CREATE OR REPLACE VIEW business.fleet AS
             SELECT fleets.id,
                fleets.code,
                fleets.name,
                fleets.invoice_header
               FROM fleets;");

        $this->addSql("ALTER TABLE business.fleet
                        OWNER TO sharengo;");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP VIEW business.fleet');
    }
}
