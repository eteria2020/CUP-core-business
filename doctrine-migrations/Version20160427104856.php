<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160427104856 extends AbstractMigration
{
    const TRIP_TABLE = 'business.business_trip';
    const TRIP_VIEW = 'business.trip';

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("
        CREATE OR REPLACE VIEW " . self::TRIP_VIEW . " AS
             SELECT trips.id,
                trips.car_plate,
                trips.customer_id,
                trips.timestamp_beginning,
                trips.km_beginning,
                trips.longitude_beginning,
                trips.latitude_beginning,
                trips.geo_beginning,
                trips.beginning_tx,
                trips.address_beginning,
                trips.timestamp_end,
                trips.km_end,
                trips.longitude_end,
                trips.latitude_end,
                trips.geo_end,
                trips.end_tx,
                trips.address_end,
                trips.park_seconds
                FROM trips
                 JOIN " . self::TRIP_TABLE . " bt ON trips.id = bt.trip_id;");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DROP VIEW " . self::TRIP_VIEW);

    }
}
