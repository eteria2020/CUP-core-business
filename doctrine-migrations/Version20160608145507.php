<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160608145507 extends AbstractMigration
{
    const MAIN_TABLE = 'business.extra_payment';
    const SEQUENCE_NAME = 'business.extra_payment_id_seq';

    const SUPPORT_TABLE = 'business.extra_payment_transaction';

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $schema->createSequence(self::SEQUENCE_NAME);
        $table = $schema->createTable(self::MAIN_TABLE);
        $table->addColumn("id", "integer", ["notnull" => true, "default" => "nextval('".self::SEQUENCE_NAME."')"]);
        $table->addColumn("business_code", "string", ["notnull" => true, "length" => 6]);
        $table->addColumn("amount", "integer", ["notnull" => true]);
        $table->addColumn("currency", "string", ["notnull" => true, "length" => 3]);
        $table->addColumn("status", "string", ["notnull" => true]);
        $table->addColumn("expected_payed_ts", "datetime", ["notnull" => false]);
        $table->addColumn("created_ts", "datetime", ["notnull" => true, "default" => 'CURRENT_TIMESTAMP']);
        $table->addColumn("payed_on_ts", "datetime", ["notnull" => false]);
        $table->addColumn("invoice_id", "integer", ["notnull" => false]);

        $table->addColumn("reason", "string", ["notnull" => false]);

        $table->setPrimaryKey(["id"]);
        $table->addForeignKeyConstraint('business.business', ['business_code'], ['code']);
        $table->addForeignKeyConstraint('business.business_invoice', ['invoice_id'], ['id']);

        $table = $schema->createTable(self::SUPPORT_TABLE);
        $table->addColumn("extra_payment_id", "integer", ["notull" => true]);
        $table->addColumn("transaction_id", "integer", ["notull" => true]);
        $table->addColumn("created_ts", "datetime", ["notnull" => true, "default" => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(["extra_payment_id", "transaction_id"]);
        $table->addForeignKeyConstraint('business.extra_payment', ['extra_payment_id'], ['id']);
        $table->addForeignKeyConstraint('business.transaction', ['transaction_id'], ['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropSequence(self::SEQUENCE_NAME);
        $schema->dropTable(self::SUPPORT_TABLE);
        $schema->dropTable(self::MAIN_TABLE);
    }
}
