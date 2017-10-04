

# Projet ORGABAT

​

Le projet ORGABAT est un projet de Serious Game à destination des apprentis CAP des Centres de Formation du Bâtiment. les objectifs de ce serious game sont multiples :

  - Accompagner par le numérique les apprentis pour qu’ils organisent bien leur poste de travail, améliorent leur efficacité et préservent leur santé durablement, en sécurité.

  - La finalité est de développer chez l’apprenti son analyse pour qu’il organise mieux son travail afin de lui permettre à la fois d’être plus performant pour son entreprise tout en restant en bonne santé.

​

## Fonctionnalités de l'application (en version finale):

  - Avoir un espace de connexion utilisateur et administrateur donnant accès à deux différents dashboards. Les utilisateurs pourront accéder aux différentes catégories et jeux. Les administrateurs eux sont séparé en deux catégories : les formateurs et les admins.

  - Les formateurs auront accès aux statistiques (concernant la réalisation d'un/des jeux) de chacun des apprentis dont il est responsable.

  - Les administrateurs pourront créer/supprimer les comptes pour les formateurs et les utilisateurs.

  - Les mini-jeux proposés auront tous un but pédagogique et seront au nombre de 3 par catégories avec un total de 8 catégories.

  - L'utilisateur naviguera dans un environnement clair et épuré et sera accompagné dans les phases de jeu par un utilisateur virtuel qui pourra le conseiller en fonction des ations de celui-ci (erreur/réussite dans une des phases du jeu).

​

## Equipe de projet

Le développement de cette application ce fera en deux phases avec deux équipes au moins partiellement différentes. La première dans le cadre d'un projet de groupe entre le Master1 DNR2i de l'Université de Caen pour la réalisation, Eve Vinclair de KNP Labs pour superviser le projet et le client M. Costard et son équipe, directeur du BTP CFA Basse-Normandie. Cette phase de développement aura une durée d'une semaine (SPRINT). La seconde phase sera plus longue et n'est pas encore définie mais ce déroulera probablement entre le client et la JuniorEntreprise (CaenJuniorBusiness) co-gérée par le département informatique de l'Université et l'IAE de Caen.

​

### Equipe de projet : Phase 1

  - Gestion de projet web : Pierre Labadille, avec l'aide d'Eve Vinclair.

  - Clients : Denis Costard (Directeur BTP CFA Basse-normandie) et Mathieu Osmont (Responsable informatique).

  - Gestion de projet technique (GIT) : Evrard Caron.

  - Developpeurs Backend : Evrard Caron (Responsable Git et algorithmique), Lenaïc Couëllan (Responsable Symfony), Yoann Boyer (JS Fullstack), Logan Lepage (JS applicatif), Thomas Fortin (PHP).

  - Developpeurs Frontend : Raphaël Erfani (Responsable Design), Florian Quaghebeur (intégration et responsable UML), Jesus Garnica (Integration).

​

### Equipe de projet : Phase 2

à définir

​

## Détails techniques

​

### Installation serveur

```
sudo apt-get install php7.0-cli php7.0-xml php-mysql mysql-server
service mysql-server start
git clone https://github.com/Five52/orgabat.git
git checkout dev
php composer.phar install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load
```

### Installation jeux

```
apt-get install curl
curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
sudo apt-get install -y nodejs -> install npm & nodejs
git submodule update --init
cd ./web/modules/game
npm install -> install dependencies
npm install -g webpack 
webpack -> build js files
```

### Lancement serveur

```
php bin/console server:start
```

### Se connecter

```
Jacques Dupont
01012001
```


​

### Tech

​

* Framework : Symfony 3

* IDE : PHP Storm, Atom

* Design : boostrap

* [jQuery]

* Moteur de jeu : Phaser

​

License

----

​

GNU GENERAL PUBLIC LICENSE

​

Groupes impliqués dans le projet:

----

​

* BTP CFA Basse-Normandie

* KNP Labs

* M1 DNR2i Université de Caen Normandie

​

Partenaires

----

​

* Région Normandie

* Union Européenne

* Carsat Normandie

* CCCA - BTP

* L'Europe s'engage en Normandie

* Société française de Médecine du travail

* OPP BTP

​

​

   [jQuery]: <http://jquery.com>

​

=======

​

A Symfony project created on May 23, 2016, 3:15 pm.

