create table if not exists admins
(
	email varchar(100) not null,
	primary key(email)
);

insert into admins(email) values ('craig@lateral.co.za');
insert into admins(email) values ('arthur@electricbookworks.com');