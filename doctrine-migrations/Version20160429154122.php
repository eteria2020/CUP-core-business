<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160429154122 extends AbstractMigration
{
    const TABLE = 'business.time_package';
    const SEQUENCE_NAME = 'business.time_package_id_seq';
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $schema->createSequence(self::SEQUENCE_NAME);
        $table = $schema->createTable(self::TABLE);
        $table->addColumn("id", "integer", ["notnull" => true, "default" => "nextval('".self::SEQUENCE_NAME."')"]);
        $table->addColumn("inserted_ts", "datetime", ["notnull" => true, "default" => 'CURRENT_TIMESTAMP']);
        $table->addColumn("minutes", "integer", ["notnull" => true]);
        $table->addColumn("cost", "integer", ["notnull" => true]);

        $table->setPrimaryKey(["id"]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable(self::TABLE);
        $schema->dropSequence(self::SEQUENCE_NAME);
    }
}
