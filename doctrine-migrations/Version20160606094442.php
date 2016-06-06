<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160606094442 extends AbstractMigration
{
    const TABLE = 'business.business_payment';
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $table->addColumn("status", "string", ["notnull" => true, 'default' => 'pending']);
        $table->addColumn("invoice_id", "integer", ["notnull" => false]);
        $table->addForeignKeyConstraint('business.business_invoice', ['invoice_id'], ['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $table->dropColumn('status');
        $table->dropColumn('invoice_id');
    }
}
