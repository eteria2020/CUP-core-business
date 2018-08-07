<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160920153252 extends AbstractMigration
{
    const TABLE = 'business.webuser';
    const SEQUENCE_NAME = 'business.webuser_id_seq';
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $schema->createSequence(self::SEQUENCE_NAME);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
    }

    public function postUp(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $this->addSql('UPDATE business.webuser SET id = business.webuser_id_seq.nextval');
        $table->changeColumn("id", ["notnull" => true, "default" => "nextval('".self::SEQUENCE_NAME."')"]);
    }
}
