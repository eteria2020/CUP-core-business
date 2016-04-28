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

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable(self::TABLE);
        $table->addColumn("invoice_id", "integer");
        $table->addColumn("business_code", "string", ["notnull" => true, "length" => 6]);
        $table->setPrimaryKey(["invoice_id", "business_code"]);
        $table->addForeignKeyConstraint('business.business', ['business_code'], ['code']);
        $table->addForeignKeyConstraint('invoices', ['invoice_id'], ['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable(self::TABLE);
    }
}
