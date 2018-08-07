<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160418180144 extends AbstractMigration
{
    const TABLE = 'business.webuser';
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $table->addColumn("business_code", "string", ["notnull" => true, "length" => 6]);
        $table->addForeignKeyConstraint('business.business', ['business_code'], ['code']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $table->dropColumn('business_code');
    }
}
