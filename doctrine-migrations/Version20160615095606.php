<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160615095606 extends AbstractMigration
{
    //to alter TYPES we must set this migration as non-transactional
    public function isTransactional()
    {
        return false;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("ALTER TYPE business.employee_status ADD VALUE 'approved_waiting_for_business_enabling';");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        //deletion of enum value is not supported in postgres
    }
}
