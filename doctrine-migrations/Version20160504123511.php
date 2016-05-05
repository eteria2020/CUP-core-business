<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160504123511 extends AbstractMigration
{
    const FARE_VIEW = 'business.fare';
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('
        CREATE OR REPLACE VIEW ' . self::FARE_VIEW .' AS
         SELECT fares.id,
            fares.motion_cost_per_minute,
            fares.park_cost_per_minute,
            fares.cost_steps
            FROM fares;
             ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DROP VIEW " . self::FARE_VIEW);
    }
}
