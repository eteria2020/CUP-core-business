<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160504123511 extends AbstractMigration
{
    const TABLE = 'business.business_rate';
    const SEQUENCE_NAME = 'business.business_rate_id_seq';
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $schema->createSequence(self::SEQUENCE_NAME);
        $table = $schema->createTable(self::TABLE);
        $table->addColumn("id", "integer", ["notnull" => true, "default" => "nextval('".self::SEQUENCE_NAME."')"]);
        $table->addColumn("business_code", "string", ["notnull" => true, "length" => 6]);
        $table->addColumn("discount_stop", "decimal", ["notnull" => true, 'precision' => 5, 'scale' => 4]);
        $table->addColumn("discount_trip", "decimal", ["notnull" => true, 'precision' => 5, 'scale' => 4]);
        $table->addColumn("inserted_ts", "datetime", ["notnull" => true, "default" => 'CURRENT_TIMESTAMP']);
        $table->addColumn("updated_ts", "datetime", ["notnull" => true, "default" => 'CURRENT_TIMESTAMP']);

        $table->setPrimaryKey(["id"]);
        $table->addForeignKeyConstraint('business.business', ['business_code'], ['code']);
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
