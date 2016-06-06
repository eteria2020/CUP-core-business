<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160606095734 extends AbstractMigration
{
    const TABLE = 'business.payment_transaction';

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable(self::TABLE);
        $table->addColumn("business_payment_id", "integer", ["notnull" => true]);
        $table->addColumn("transaction_id", "integer", ["notnull" => true]);
        $table->setPrimaryKey(["business_payment_id", "transaction_id"]);

        $table->addForeignKeyConstraint('business.business_payment', ['business_payment_id'], ['id']);
        $table->addForeignKeyConstraint('business.transaction', ['transaction_id'], ['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable(self::TABLE);
    }
}
