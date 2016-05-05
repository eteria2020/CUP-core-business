<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160428114826 extends AbstractMigration
{
    const TABLE = 'business.business_invoice';
    const SEQUENCE_NAME = 'business.business_invoice_id_seq';

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $schema->createSequence(self::SEQUENCE_NAME);
        $table = $schema->createTable(self::TABLE);
        $table->addColumn("id", "integer", ["notnull" => true, "default" => "nextval('".self::SEQUENCE_NAME."')"]);
        $table->addColumn("invoice_number", "integer", ["notnull" => true]);
        $table->addColumn("business_code", "string", ["notnull" => true, "length" => 6]);
        $table->addColumn("generated_ts", "datetime", ["notnull" => true, "default" => 'CURRENT_TIMESTAMP']);
        $table->addColumn("content", "json_array", ["notnull" => true]);
        $table->addColumn("version", "integer", ["notnull" => true]);
        $table->addColumn("type", "string", ["notnull" => true]);
        $table->addColumn("invoice_date", "integer", ["notnull" => true]);
        $table->addColumn("amount", "integer", ["notnull" => true]);
        $table->addColumn("iva", "integer", ["notnull" => true]);

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
