create table admin (
    id_admin int primary key auto_increment,
    nom_admin varchar(50) not null,
    prenom_admin varchar(50) not null,
    email_admin varchar(100) not null,
    mot_de_passe_admin varchar(255) not null,
    is_admin boolean default true
);