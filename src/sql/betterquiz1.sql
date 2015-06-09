create table user (
	id bigint auto_increment not null,
	fullname varchar(250) not null,
	email varchar(100) null,
	mobile varchar(100) null,
	hash varchar(256) not null,
	regdate datetime not null,
	primary key(id)
);

create table exam (
	id bigint auto_increment not null,
	quiz_id bigint not null,
	user_id bigint not null,
	startdate datetime not null,
	enddate datetime null,
	submitted tinyint default 0,
	primary key(id),
	foreign key(quiz_id) references quiz(id)
		on delete cascade,
	foreign key(user_id) references user(id)
		on delete cascade
);

create table answer (
	exam_id bigint not null,
	option_id bigint not null,
	primary key(exam_id, option_id),
	foreign key(exam_id) references exam(id)
		on delete cascade,
	foreign key(option_id) references option(id)
		on delete cascade
);
