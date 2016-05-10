<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;


class Version20160429124151 extends AbstractMigration
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
        $this->addSql("ALTER TYPE business.employee_status ADD VALUE 'deleted';");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        //deletion of enum value is not supported in postgres
    }
}
