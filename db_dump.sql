create schema moore_advice collate utf8mb4_general_ci;

create table states
(
	id int auto_increment
		primary key,
	name varchar(100) not null,
	created_at datetime default current_timestamp() null,
	updated_at datetime default current_timestamp() null on update current_timestamp()
);

create table users
(
	id int auto_increment
		primary key,
	name varchar(300) not null,
	code varchar(100) not null,
	created_at datetime default current_timestamp() null,
	updated_at datetime default current_timestamp() null on update current_timestamp(),
	constraint users_code_uindex
		unique (code)
);

create table applications
(
	id int auto_increment
		primary key,
	user_id int not null,
	first_name varchar(100) not null,
	last_name varchar(100) not null,
	address varchar(300) not null,
	marital_status enum('Single', 'Married') not null,
	education varchar(200) not null,
	best_subjects longtext collate utf8mb4_bin not null,
	religion varchar(100) not null,
	state_of_origin int not null,
	dob date not null,
	image text not null,
	created_at datetime default current_timestamp() null,
	updated_at datetime default current_timestamp() null on update current_timestamp(),
	constraint applicants_states_id_fk
		foreign key (state_of_origin) references states (id),
	constraint applicants_users_id_fk
		foreign key (user_id) references users (id)
			on update cascade on delete cascade,
	constraint best_subjects
		check (json_valid(`best_subjects`))
);

