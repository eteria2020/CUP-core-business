<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160606095128 extends AbstractMigration
{
    const TABLE = 'business.transaction';
    const SEQUENCE_NAME = 'business.transaction_id_seq';

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $schema->createSequence(self::SEQUENCE_NAME);
        $table = $schema->createTable(self::TABLE);
        $table->addColumn("id", "integer", ["notnull" => true, "default" => "nextval('".self::SEQUENCE_NAME."')"]);
        $table->addColumn("currency", "string", ["notnull" => true, "length" => 3]);
        $table->addColumn("amount", "integer", ["notnull" => true]);
        $table->addColumn("outcome", "string", ["notnull" => false]);
        $table->addColumn("created_ts", "datetime", ["notnull" => true, "default" => 'CURRENT_TIMESTAMP']);
        $table->addColumn("outcome_ts", "datetime", ["notnull" => false]);

        $table->setPrimaryKey(["id"]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropSequence(self::SEQUENCE_NAME);
        $schema->dropTable(self::TABLE);
    }
}
