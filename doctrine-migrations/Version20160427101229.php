<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160427101229 extends AbstractMigration
{
    const TABLE = 'business.business_trip';
    const SEQUENCE_NAME = 'business.business_trip_id_seq';

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $schema->createSequence(self::SEQUENCE_NAME);
        $table = $schema->createTable(self::TABLE);
        $table->addColumn("id", "integer", ["notnull" => true, "default" => "nextval('".self::SEQUENCE_NAME."')"]);
        $table->addColumn("trip_id", "integer");
        $table->addColumn("business_code", "string", ["notnull" => true, "length" => 6]);
        $table->addColumn("group_id", "integer", ["notnull" => false]);
        $table->setPrimaryKey(["id"]);
        $table->addForeignKeyConstraint('business.business', ['business_code'], ['code']);
        $table->addForeignKeyConstraint('business.employee_group', ['group_id'], ['id']);
        $table->addForeignKeyConstraint('trips', ['trip_id'], ['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable(self::TABLE);
        $schema->dropSequence(self::SEQUENCE_NAME);
    }
}
