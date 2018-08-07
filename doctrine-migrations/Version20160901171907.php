<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160901171907 extends AbstractMigration
{
    const TABLE = 'business.business';

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $table->addColumn("invoice_frequence", "string", ["notnull" => false]);
        $table->addColumn("last_payment_execution", "datetime", ["notnull" => false]);
        $table->addColumn("last_invoice_execution", "datetime", ["notnull" => false]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $table->dropColumn("invoice_frequence");
        $table->dropColumn("last_payment_execution");
        $table->dropColumn("last_invoice_execution");
    }
}
