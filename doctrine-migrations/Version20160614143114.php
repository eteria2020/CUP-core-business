<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160614143114 extends AbstractMigration
{
    const TABLE = 'business.contract';
    const SEQUENCE_NAME = 'business.contract_id_seq';

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $schema->createSequence(self::SEQUENCE_NAME, 1, 10000);
        $table = $schema->createTable(self::TABLE);
        $table->addColumn("id", "integer", ["notnull" => true, "default" => "nextval('".self::SEQUENCE_NAME."')"]);
        $table->addColumn("business_code", "string", ["notnull" => true, "length" => 6]);
        $table->addColumn("pan_expiry", "string", ["notnull" => false, "length" => 6]);
        $table->addColumn("created_ts", "datetime", ["notnull" => true, "default" => 'CURRENT_TIMESTAMP']);
        $table->addColumn("disabled_date", "datetime", ["notnull" => false]);

        $table->setPrimaryKey(["id"]);
        $table->addForeignKeyConstraint('business.business', ['business_code'], ['code']);
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
