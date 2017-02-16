<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160714125314 extends AbstractMigration
{
    const TABLE = 'business.business_invoice';
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $table->addColumn("fleet_id", "integer", ["notnull" => true]);
        $table->addForeignKeyConstraint('fleets', ['fleet_id'], ['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $table->dropColumn("fleet_id");
    }
}
