<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160616162803 extends AbstractMigration
{
    const TABLE = 'business.business_invoice';
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $table->dropColumn("iva");
        $table->addColumn("vat", "integer", ["notnull" => true]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $table->dropColumn("vat");
        $table->addColumn("iva", "integer", ["notnull" => true]);
    }
}
