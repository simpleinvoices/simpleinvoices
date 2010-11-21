create table if not exists sales (
id int(5),
name varchar(50),
city varchar(50),
type varchar(10),
item varchar(50),
value decimal(15,2)
);

insert into sales values (1,"Eustaquio Rangel","Sao Jose do Rio Preto, SP","Book","Linux Programming",25);
insert into sales values (1,"Eustaquio Rangel","Sao Jose do Rio Preto, SP","Book","Design Patterns",35);
insert into sales values (1,"Eustaquio Rangel","Sao Jose do Rio Preto, SP","CD","Primus - Antipop",12);
insert into sales values (1,"Eustaquio Rangel","Sao Jose do Rio Preto, SP","CD","Machine Head - Elegies",12);
insert into sales values (1,"Eustaquio Rangel","Sao Jose do Rio Preto, SP","DVD","V for Vendetta",20);
insert into sales values (2,"Ana Carolina" ,"Sao Jose do Rio Preto, SP","Book","Photoshop",22.5);
insert into sales values (2,"Ana Carolina" ,"Sao Jose do Rio Preto, SP","Book","Learning Ubuntu Linux",15);
insert into sales values (2,"Ana Carolina" ,"Sao Jose do Rio Preto, SP","CD","Ramones - Loco Live",12);
insert into sales values (3,"Andre Kada" ,"Sao Paulo, SP","CD","Kreator - Violent Revolutions",15);
insert into sales values (3,"Andre Kada" ,"Sao Paulo, SP","CD","Kreator - Enemy of God",15);
