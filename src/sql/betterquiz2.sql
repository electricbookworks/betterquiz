create table if not exists user_forgot (
	uid bigint not null,
	email varchar(100) null,
	mobile varchar(100) null,
	hash varchar(256) not null,
	expiry datetime not null
);

create index uf_uid on user_forgot(uid);
create index uf_email on user_forgot(email, expiry);
create index uf_mobile on user_forgot(mobile, expiry);
