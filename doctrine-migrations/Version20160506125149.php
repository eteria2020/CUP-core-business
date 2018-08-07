<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160506125149 extends AbstractMigration
{
    const TABLE = 'business.business_buyable_time_package';
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable(self::TABLE);
        $table->addColumn("business_code", "string", ["notnull" => true, "length" => 6]);
        $table->addColumn("time_package_id", "integer", ["notnull" => true]);
        $table->addColumn("inserted_ts", "datetime", ["notnull" => true, "default" => 'CURRENT_TIMESTAMP']);

        $table->setPrimaryKey(["business_code", "time_package_id"]);
        $table->addForeignKeyConstraint('business.business', ['business_code'], ['code']);
        $table->addForeignKeyConstraint('business.time_package', ['time_package_id'], ['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable(self::TABLE);
    }
}
