CREATE TABLE CART(
  custid int NOT NULL,
  prodid int NOT NULL,
  quantity varchar(255),
  FOREIGN KEY(custid) REFERENCES CUSTOMER(id),
  FOREIGN KEY(prodid) REFERENCES PRODINFO(id)
);
