
create table if not exists quiz (
	id bigint auto_increment not null,
	title varchar(1024) not null,
	primary key(id)
);

create table if not exists quiz_meta (
	quiz_id bigint not null,
	meta_key varchar(100) not null,
	meta_value varchar(100) not null,
	primary key(quiz_id, meta_key),
	foreign key(quiz_id) references quiz(id)
		on delete cascade
);

create table if not exists question (
	id bigint auto_increment not null,
	quiz_id bigint not null,
	question_text text not null,
	question_number int not null,
	primary key(id),
	foreign key(quiz_id) references quiz(id)
		on delete cascade
);

create table if not exists options (
	id bigint auto_increment not null,
	question_id bigint not null,
	option_text text not null,
	option_number int not null,
	correct tinyint default 0,
	primary key(id),
	foreign key(question_id) references question(id)
		on delete cascade
);
