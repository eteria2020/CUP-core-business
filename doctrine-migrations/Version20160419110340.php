<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160419110340 extends AbstractMigration
{
    const TABLE = 'business.employee_group';
    const SEQUENCE_NAME = 'business.group_id_seq';

    const BUSINESS_EMPLOYEE_TABLE = 'business.business_employee';

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $schema->createSequence(self::SEQUENCE_NAME);
        $table = $schema->createTable(self::TABLE);
        $table->addColumn("id", "integer", ["notnull" => true, "default" => "nextval('".self::SEQUENCE_NAME."')"]);
        $table->addColumn("business_code", "string", ["notnull" => true, "length" => 6]);
        $table->addColumn("name", "string", ["notnull" => true]);
        $table->addColumn("description", "string", ["notnull" => true ]);
        $table->addColumn("created_ts", "datetime", ["notnull" => true, "default" => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(["id"]);
        $table->addForeignKeyConstraint('business.business', ['business_code'], ['code']);
        $table->addUniqueIndex(["name"]);

        $businessEmployeeTable = $schema->getTable(self::BUSINESS_EMPLOYEE_TABLE);

        $businessEmployeeTable->addColumn("group_id", "integer", ["notnull" => false]);
        $businessEmployeeTable->addForeignKeyConstraint(self::TABLE, ['group_id'], ['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $businessEmployeeTable = $schema->getTable(self::BUSINESS_EMPLOYEE_TABLE);
        $businessEmployeeTable->dropColumn("group_id");
        $schema->dropSequence(self::SEQUENCE_NAME);
        $schema->dropTable(self::TABLE);
    }
}
