drop database if exists supportme;
create database supportme;

use supportme;

create table user (
    user_id int auto_increment not null,
    firstname   varchar(255) not null,
    lastname varchar(255),
    email varchar(255) not null,
    pw_hash varchar (255) not null,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    constraint userPK primary key (user_id)
);

create table campaign (
    campaign_id int auto_increment not null,
    starter_id int not null,
    c_title varchar(255) not null,
    c_description text,
    c_picture varchar(255),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    constraint campaignPK primary key (campaign_id),
    constraint campaignFK foreign key (starter_id) references user(user_id)
);

create table comment (
    commenter_id int not null,
    campaign_id int not null,

    comment_text text,

    reply_id int,

    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    constraint commentPK primary key (createdAt, commenter_id, campaign_id),
    constraint commentFK1 foreign key (commenter_id) references user(user_id),
    constraint commentFK2 foreign key (campaign_id) references campaign(campaign_id),
    constraint commentFK3 foreign key (reply_id) references campaign(campaign_id)
);

create table pledgers (
    pledger_id int not null,
    campaign_id int not null,
    createdat timestamp DEFAULT CURRENT_TIMESTAMP,
    pledge_reason text,
    constraint pledgersPK primary key (pledger_id, campaign_id),
    constraint pledgerFK1 foreign key (pledger_id) references user(user_id),
    constraint pledgerFK2 foreign key (campaign_id) references campaign(campaign_id)
);


create table session (
    user_id int not null,
    device varchar(255) not null,
    session_data text not null,
    createdat timestamp DEFAULT CURRENT_TIMESTAMP,
    TTL timestamp not null,
    constraint sessionPK primary key (user_id,createdAt),
    constraint sessionFK foreign key (user_id) references user(user_id),

);

insert into user values(DEFAULT,'Joshua','Sumarlin','joshua@lol.org',123,DEFAULT,DEFAULT);
insert into user values(DEFAULT,'Neil','Sharma','neil@lol.org',456,DEFAULT,DEFAULT);
insert into campaign values(DEFAULT,1,'Save my grades','Help me keep my scholarship',NULL,DEFAULT,DEFAULT);
insert into pledgers values (1,1,DEFAULT,DEFAULT);
insert into pledgers values (2,1,DEFAULT,DEFAULT);
insert into comment values(1,1,'cake dog cat',NULL,DEFAULT,DEFAULT);
insert into comment values (2,1,'funny animals',1,DEFAULT,DEFAULT);
insert into session(user_id,device,session_data,TTL) values (1,'chrome','abcdef','2022-12-16 16:49:46');