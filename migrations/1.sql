CREATE SCHEMA businesses
       AUTHORIZATION sharengo;
GRANT ALL ON SCHEMA businesses TO public;

CREATE TYPE business_payment_type AS ENUM ('Bonifico', 'Carta di credito');
CREATE TYPE business_payment_frequence AS ENUM ('Settimanale', 'Quindicinale', 'Mensile');

CREATE TABLE businesses.business
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
  payment_frequence business_payment_frequence,
  payment_type business_payment_type,
  business_mail_control bool,
  inserted_ts timestamp with time zone DEFAULT now(),
  updated_ts timestamp with time zone
);

ALTER TABLE businesses.business
  OWNER TO sharengo;