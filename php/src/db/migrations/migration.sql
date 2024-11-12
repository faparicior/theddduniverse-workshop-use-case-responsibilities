create table main.advertisements
(
    id TEXT not null constraint advertisements_pk primary key,
    description TEXT,
    password TEXT,
    email TEXT,
    advertisement_date TEXT
);

create table main.users
(
    id TEXT not null constraint advertisements_pk primary key,
    email TEXT,
    password TEXT,
    role TEXT,
    member_number TEXT,
    civic_center_id TEXT
);

