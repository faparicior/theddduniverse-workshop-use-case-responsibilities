create table main.advertisements
(
    id TEXT not null constraint advertisements_pk primary key,
    description TEXT,
    password TEXT,
    email TEXT,
    advertisement_date TEXT
);
