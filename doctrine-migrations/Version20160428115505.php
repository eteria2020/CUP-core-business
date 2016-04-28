<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160428115505 extends AbstractMigration
{

    const INVOICE_VIEW = 'business.invoice';
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('
        CREATE OR REPLACE VIEW ' . self::INVOICE_VIEW .' AS
         SELECT invoices.id,
            invoices.invoice_number,
            invoices.customer_id,
            invoices.generated_ts,
            invoices.content,
            invoices.version,
            invoices.type,
            invoices.invoice_date,
            invoices.amount,
            invoices.iva
            FROM invoices
                JOIN business.business_invoice bi ON invoices.id = bi.invoice_id;
             ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DROP VIEW " . self::INVOICE_VIEW);
    }
}
