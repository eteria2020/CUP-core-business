<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160613145641 extends AbstractMigration
{
    const TABLE = 'business.business';
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $table->addColumn("is_enabled", "boolean", ["notnull" => true, "default" => false]);
        $table->addColumn("subscription_fee_cents", "integer", ["notnull" => true, "default" => 50000]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $table->dropColumn('is_enabled');
        $table->dropColumn('subscription_fee_cents');
    }
}
