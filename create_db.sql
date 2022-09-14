create table albums
(
    id_album bigint auto_increment
        primary key,
    name     varchar(255) not null
)
    auto_increment = 3;

create table genres
(
    id_genre bigint auto_increment
        primary key,
    name     varchar(255) not null
)
    auto_increment = 3;

create table songs
(
    id_song    bigint auto_increment
        primary key,
    name       varchar(255)                        not null,
    id_album   bigint                              not null,
    id_genre   bigint                              not null,
    song_url   varchar(480)                        not null,
    date_added timestamp default CURRENT_TIMESTAMP not null,
    constraint fk_albums_songs
        foreign key (id_album) references albums (id_album)
            on update cascade,
    constraint fk_genres_songs
        foreign key (id_genre) references genres (id_genre)
            on update cascade
)
    auto_increment = 3;

create table tags
(
    id_tag bigint auto_increment
        primary key,
    name   varchar(255) not null
);

create table albums_tags
(
    id_at    bigint auto_increment
        primary key,
    id_tag   bigint not null,
    id_album bigint not null,
    constraint fk_at_albums
        foreign key (id_album) references albums (id_album),
    constraint fk_at_tags
        foreign key (id_tag) references tags (id_tag)
);

create table songs_tags
(
    id_st   bigint auto_increment
        primary key,
    id_tag  bigint not null,
    id_song bigint not null,
    constraint fk_st_songs
        foreign key (id_song) references songs (id_song),
    constraint fk_st_tags
        foreign key (id_tag) references tags (id_tag)
);

create table users
(
    id_user         bigint auto_increment
        primary key,
    identifier      varchar(255)                        not null,
    username        varchar(255)                        not null,
    email           varchar(255)                        not null,
    password        varchar(480)                        not null,
    name            varchar(255)                        null,
    bio             text                                null,
    date_registered timestamp default CURRENT_TIMESTAMP not null,
    is_admin        int       default 0                 null,
    profile_pic_url varchar(480)                        null
)
    auto_increment = 2;

create table artists
(
    id_artist bigint auto_increment
        primary key,
    id_user   bigint       null,
    name      varchar(255) not null,
    constraint uk_artists
        unique (name),
    constraint fk_artists_users
        foreign key (id_user) references users (id_user)
            on update cascade
)
    auto_increment = 7;

create table albums_artists
(
    id_aa     bigint auto_increment
        primary key,
    id_album  bigint not null,
    id_artist bigint not null,
    constraint fk_aa_album
        foreign key (id_album) references albums (id_album)
            on update cascade,
    constraint fk_aa_artist
        foreign key (id_artist) references artists (id_artist)
            on update cascade
)
    auto_increment = 4;

create table comments_songs
(
    id_comment bigint auto_increment
        primary key,
    id_song    bigint                              not null,
    id_user    bigint                              not null,
    content    text                                not null,
    hidden     int       default 0                 null,
    id_parent  bigint                              null,
    date_time  timestamp default CURRENT_TIMESTAMP not null,
    constraint fk_cs_songs
        foreign key (id_song) references songs (id_song)
            on update cascade,
    constraint fk_cs_users
        foreign key (id_user) references users (id_user)
            on update cascade
)
    auto_increment = 2;

create table songs_artists
(
    id_sa     bigint auto_increment
        primary key,
    id_song   bigint not null,
    id_artist bigint not null,
    constraint fk_sa_artists
        foreign key (id_artist) references artists (id_artist)
            on update cascade,
    constraint fk_sa_song
        foreign key (id_song) references songs (id_song)
            on update cascade
)
    auto_increment = 7;

create table songs_saves
(
    id_ss       bigint auto_increment
        primary key,
    id_song     bigint                              not null,
    id_user     bigint                              not null,
    star_rating int       default 0                 not null,
    date_saved  timestamp default CURRENT_TIMESTAMP not null,
    constraint fk_ss_songs
        foreign key (id_song) references songs (id_song)
            on update cascade,
    constraint fk_ss_users
        foreign key (id_user) references users (id_user)
            on update cascade
)
    auto_increment = 3;
