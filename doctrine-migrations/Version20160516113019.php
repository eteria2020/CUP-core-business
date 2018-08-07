<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160516113019 extends AbstractMigration
{
    const TABLE = 'business.business_invoice_number';
    const SEQUENCE_NAME = 'business.business_invoice_number_id_seq';

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $schema->createSequence(self::SEQUENCE_NAME);
        $table = $schema->createTable(self::TABLE);
        $table->addColumn("id", "integer", ["notnull" => true, "default" => "nextval('".self::SEQUENCE_NAME."')"]);
        $table->addColumn("year", "integer", ["notnull" => true]);
        $table->addColumn("fleet_id", "integer", ["notnull" => true]);
        $table->addColumn("number", "integer", ["notnull" => true]);

        $table->setPrimaryKey(["id"]);
        $table->addForeignKeyConstraint('fleets', ['fleet_id'], ['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropSequence(self::SEQUENCE_NAME);
        $schema->dropTable(self::TABLE);
    }
}
