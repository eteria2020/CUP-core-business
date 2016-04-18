<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160418155354 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("CREATE TYPE business.payment_type AS ENUM ('wire_transfer', 'credit_card');");
        $this->addSql("CREATE TYPE business.payment_frequence AS ENUM ('weekly', 'fortnightly', 'monthly');");
        $this->addSql("CREATE TABLE business.webuser
            (
              id integer NOT NULL,
              email character varying(100) NOT NULL,
              display_name character varying(100) NOT NULL,
              password character varying(64) NOT NULL,
              role character varying(100) NOT NULL,
              CONSTRAINT webuser_pkey PRIMARY KEY (id)
            );");
        $this->addSql("ALTER TABLE business.webuser
              OWNER TO sharengo;");
        $this->addSql("CREATE TABLE business.business
            (
              code VARCHAR(6) PRIMARY KEY,
              name VARCHAR(64) NOT NULL,
              domains jsonb,
              address VARCHAR(128) NOT NULL,
              zip_code VARCHAR(12) NOT NULL,
              province VARCHAR(2) NOT NULL,
              city VARCHAR(64) NOT NULL,
              vat_number VARCHAR(64) NOT NULL,
              email VARCHAR(255),
              phone VARCHAR(64),
              fax VARCHAR(64),
              payment_frequence business.payment_frequence,
              payment_type business.payment_type,
              business_mail_control bool,
              inserted_ts timestamp with time zone DEFAULT now(),
              updated_ts timestamp with time zone
            );");

        $this->addSql("ALTER TABLE business.business
              OWNER TO sharengo;");

        $this->addSql("CREATE TYPE business.employee_status AS ENUM ('pending', 'approved', 'blocked');");
        $this->addSql("CREATE TABLE business.business_employee
            (
              employee_id integer,
              business_code character varying,
              status business.employee_status NOT NULL,
              confirmed_ts timestamp with time zone DEFAULT NULL,
              inserted_ts timestamp with time zone,
              CONSTRAINT tb_PK PRIMARY KEY (employee_id, business_code),
              CONSTRAINT business_code FOREIGN KEY (business_code)
                  REFERENCES business.business (code) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE NO ACTION,
              CONSTRAINT employee_id FOREIGN KEY (employee_id)
                  REFERENCES customers (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE NO ACTION
            );");

        $this->addSql("ALTER TABLE business.business_employee
              OWNER TO sharengo;");

        $this->addSql("CREATE OR REPLACE VIEW business.employee AS
             SELECT customers.id,
                customers.email,
                customers.name,
                customers.surname,
                customers.gender,
                customers.birth_date,
                customers.birth_town,
                customers.birth_province,
                customers.birth_country,
                customers.vat,
                customers.tax_code,
                customers.language,
                customers.country,
                customers.province,
                customers.town,
                customers.address,
                customers.address_info,
                customers.zip_code,
                customers.phone,
                customers.mobile,
                customers.fax,
                customers.inserted_ts,
                customers.update_ts
                FROM customers
                 JOIN business.business_employee be ON customers.id = be.employee_id;");

        $this->addSql("ALTER TABLE business.employee
              OWNER TO sharengo;
            ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}
