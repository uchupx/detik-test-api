CREATE TABLE transactions (
  id INT NOT NULL AUTO_INCREMENT,
  invoice_id INT NOT NULL,
  item_name VARCHAR(255) NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  payment_type ENUM('virtual_account', 'credit_card') NOT NULL,
  customer_name VARCHAR(255) NOT NULL,
  merchant_id INT NOT NULL,
  references_id INT NOT NULL UNIQUE,
  number_va INT,
  `status` ENUM('pending', 'paid', 'expired', 'failed') NOT NULL,
  PRIMARY KEY (id)
);
INSERT INTO transactions (
    invoice_id,
    item_name,
    amount,
    payment_type,
    customer_name,
    merchant_id,
    references_id
  )
VALUES (
    1,
    'Item 1',
    10.50,
    'virtual_account',
    'John Doe',
    1,
    12312314
  ),
  (
    2,
    'Item 2',
    20.00,
    'credit_card',
    'Jane Smith',
    2,
    123451231
  ),
  (
    3,
    'Item 3',
    15.75,
    'virtual_account',
    'Bob Johnson',
    1,
    456123451
  ),
  (
    4,
    'Item 4',
    5.99,
    'credit_card',
    'Alice Brown',
    3,
    12312412
  );