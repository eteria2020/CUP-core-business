<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160531173919 extends AbstractMigration
{
    const TABLE = 'business.employee_group';
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $table->addColumn("daily_limit", "integer", ["notnull" => false]);
        $table->addColumn("weekly_limit", "integer", ["notnull" => false]);
        $table->addColumn("monthly_limit", "integer", ["notnull" => false]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $table->dropColumn('daily_limit');
        $table->dropColumn('weekly_limit');
        $table->dropColumn('monthly_limit');
    }
}
