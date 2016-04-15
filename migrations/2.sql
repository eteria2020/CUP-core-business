CREATE TYPE employee_status AS ENUM ('pending', 'approved', 'blocked');

CREATE TABLE businesses.business_employee
(
  employee_id integer,
  business_code character varying,
  status employee_status NOT NULL,
  confirmed_ts timestamp with time zone DEFAULT NULL,
  inserted_ts timestamp with time zone,
  CONSTRAINT tb_PK PRIMARY KEY (employee_id, business_code),
  CONSTRAINT business_code FOREIGN KEY (business_code)
      REFERENCES businesses.business (code) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT employee_id FOREIGN KEY (employee_id)
      REFERENCES customers (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
);

ALTER TABLE businesses.business_employee
  OWNER TO sharengo;

CREATE OR REPLACE VIEW businesses.employee AS
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
     JOIN businesses.business_employee be ON customers.id = be.employee_id;

ALTER TABLE businesses.employee
  OWNER TO sharengo;