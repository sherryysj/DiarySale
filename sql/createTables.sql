

CREATE TABLE admin (
    username   VARCHAR2(32 CHAR) Primary Key,
    password   VARCHAR2(16 CHAR) NOT NULL,
    name       VARCHAR2(32 CHAR) NOT NULL,
    email      VARCHAR2(32 CHAR) NOT NULL,
    status     VARCHAR2(16 CHAR) NOT NULL
);


CREATE TABLE customer (
    username                 VARCHAR2(32 CHAR) NOT NULL primary key,
    password                 VARCHAR2(16 CHAR) NOT NULL,
    name                     VARCHAR2(32 CHAR) NOT NULL,
    primaryshippingaddress   VARCHAR2(128 CHAR),
    mailaddress              VARCHAR2(128 CHAR) NOT NULL,
    email                    VARCHAR2(32 CHAR) NOT NULL,
    status                   VARCHAR2(16 CHAR) NOT NULL
);


CREATE TABLE product (
    productid VARCHAR2(32 CHAR) primary key,
	productname VARCHAR2(32 CHAR) NOT NULL
);


CREATE TABLE parameter (
    parametername       VARCHAR2(32 CHAR) primary key,
    product_productid   VARCHAR2(32 CHAR) NOT NULL,
                        CONSTRAINT parameter_product_fk
                        REFERENCES product ( productid )
);


CREATE TABLE Options (
    optionname                VARCHAR2(32 CHAR) primary key,
    price                     NUMBER NOT NULL,
    availability              CHAR(1) NOT NULL,
    customizeable             CHAR(1) NOT NULL,
    parameter_parametername   VARCHAR2(32 CHAR) NOT NULL
                                CONSTRAINT option_parameter_fk 
                                REFERENCES parameter ( parametername )
);

--sequence for orders table to ensure unqiue order IDs
CREATE SEQUENCE order_seq START WITH 1000 INCREMENT BY 1 NOMAXVALUE NOCYCLE CACHE 10;

CREATE TABLE Orders (
    orderid             VARCHAR2(32) PRIMARY KEY,
    ordertime           TIMESTAMP DEFAULT (CURRENT_TIMESTAMP) NOT NULL,
    orderstatus         VARCHAR2(16 CHAR) DEFAULT 'Placed' NOT NULL,
    deliverymethod      VARCHAR2(16CHAR) DEFAULT 'Standard' NOT NULL,
    shippeddate         TIMESTAMP,
    totalprice          NUMBER NOT NULL,
    paymentmethod       VARCHAR2(16 CHAR),
    shippingaddress     VARCHAR2(128 CHAR) NOT NULL,
    feedback            VARCHAR2(256 CHAR),
    customer_username   VARCHAR2(32 CHAR) NOT NULL
                        CONSTRAINT order_customer_fk 
                        REFERENCES customer ( username )
);

--trigger to generate new order ID on each insert to table
CREATE OR REPLACE TRIGGER order_id_on_insert
    BEFORE INSERT ON Orders
    FOR EACH ROW
BEGIN
    SELECT CONCAT('O', order_seq.NEXTVAL)   
    INTO :NEW.orderid
    FROM dual;
END;

CREATE TABLE orderline (
    orderlineid         VARCHAR2(32 CHAR) PRIMARY KEY,
    quantity            INTEGER NOT NULL,
    price               NUMBER NOT NULL,
    productsummary      VARCHAR2(4000) NOT NULL,
    order_orderid       VARCHAR2(32 CHAR) NOT NULL
                        CONSTRAINT orderline_order_fk 
                        REFERENCES Orders ( orderid ),
    product_productid   VARCHAR2(32 CHAR) NOT NULL
                        CONSTRAINT orderline_product_fk
                        REFERENCES Product ( productid )
);



