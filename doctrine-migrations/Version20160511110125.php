<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160511110125 extends AbstractMigration
{
    const TABLE = 'business.business';
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $table->addColumn("employee_association_code", "string", ["notnull" => false]);
        $table->addUniqueIndex(["employee_association_code"]);

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
                FROM customers;");

        $this->addSql("ALTER TABLE business.employee
              OWNER TO sharengo;
            ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $table = $schema->getTable(self::TABLE);
        $table->dropColumn('employee_association_code');
    }
}
