create table users (
       id int(11) not null auto_increment,
       email varchar(255) not null,    -- Email is the login
       password varchar(255) not null, -- Should be hashed.
       firstname varchar(255),
       lastname varchar(255),
       is_admin boolean not null default false,
       unique (email),
       primary key (id)
);

create table events (
       id int(11) not null auto_increment,
       organizer int(11) not null,
       name varchar(100) not null,
       start_time time not null,
       end_time time not null,
       type enum('public', 'private') not null,
       primary key (id),
       foreign key (organizer) references users(id)
);

create table invitations (
       user_id int(11) not null,
       event_id int(11) not null,
       primary key (user_id, event_id),
       foreign key (user_id) references users(id),
       foreign key (event_id) references events(id)
);

-- The possible dates for an event.
create table event_dates (
       id int(11) not null auto_increment,
       event_id int(11),
       date date,
       primary key (id),
       foreign key (event_id) references events(id)
);

-- Mapping users with events and time slots.
create table reservations (
       id int(11) not null auto_increment,
       user_id int(11) not null,
       event_date_id int(11) not null,
       primary key (id),
       foreign key (user_id) references users(id),
       foreign key (event_date_id) references event_dates(id)
);


-- Some initial data
insert into users (id, email, password, firstname, lastname, is_admin)
   values(1, "foleybov@iro.umontreal.ca", sha1("foleybov"), "Vincent", "Foley-Bouron", true),
         (2, "phamlemi@iro.umontreal.ca", sha1("phamlemi"), "Truong", "Pham", false),
         (3, "vaudrypl@iro.umontreal.ca", sha1("vaudrypl"), "Pierre-Luc", "Vaudry", false),
         (4, "lapalme@iro.umontreal.ca", sha1("lapalme"), "Guy", "Lapalme", false);

insert into events(id, organizer, name, start_time, end_time, type)
   values(1, 1, "Faire TP3", "8:00:00", "22:00:00", 'public'),
         (2, 4, "Examen final", "8:00:00", "15:00:00", 'private');

insert into invitations(user_id, event_id)
   values(1, 1), (2, 1),
         (1, 2), (2, 2), (3, 2), (4, 2);

insert into event_dates(id, event_id, date)
   values(1, 1, "2012-12-06"),
         (2, 1, "2012-12-07"),
         (3, 1, "2012-12-08"),
         (4, 2, "2013-01-13"),
         (5, 2, "2013-01-14"),
         (6, 2, "2013-01-15"),
         (7, 2, "2013-01-16");
