<?php

namespace App\Roles;

trait AdminRolesTrait
{

    public static $roles = array(
        'Gestion des utilisateurs' => array(
            'Affichage des utilisateurs' => 'ROLE_AFFICHER_UTILISATEUR',
            'Ajouter des utilisateurs' => 'ROLE_AJOUTER_UTILISATEUR',
            'Modifier des utilisateurs' => 'ROLE_MODIFIER_UTILISATEUR',
            'Reset mot de passe' => 'ROLE_RESET_PWD_UTILISATEUR',
            'Connexion rapide' => 'ROLE_FAST_CONNEXION_UTILISATEUR',
            'Changer rôle d\'accès' => 'ROLE_CHANGE_ROLE_UTILISATEUR',
            'Supprimer des utilisateurs' => 'ROLE_SUPPRIMER_UTILISATEUR',
        ),
        'Gestion des groupes' => array(
            'Affichage des groupes' => 'ROLE_AFFICHER_GROUPE',
            'Ajouter des groupes' => 'ROLE_AJOUTER_GROUPE',
            'Modifier des groupes' => 'ROLE_MODIFIER_GROUPE',
            'Supprimer des groupes' => 'ROLE_SUPPRIMER_GROUPE',
        ),
        'Gestion des fonctions' => array(
            'Affichage des fonctions' => 'ROLE_AFFICHER_FONCTION',
            'Ajouter des fonctions' => 'ROLE_AJOUTER_FONCTION',
            'Modifier des fonctions' => 'ROLE_MODIFIER_FONCTION',
            'Supprimer des fonctions' => 'ROLE_SUPPRIMER_FONCTION',
        ),
        'Gestion des regions' => array(
            'Affichage des regions' => 'ROLE_AFFICHER_REGION',
            'Ajouter des regions' => 'ROLE_AJOUTER_REGION',
            'Modifier des regions' => 'ROLE_MODIFIER_REGION',
            'Supprimer des regions' => 'ROLE_SUPPRIMER_REGION',
        ),
        'Gestion des Types Nomenclature' => array(
            'Affichage des Types Nomenclature' => 'ROLE_AFFICHER_TYPE_NOMENCLATURE',
            'Ajouter des Types Nomenclature' => 'ROLE_AJOUTER_TYPE_NOMENCLATURE',
            'Modifier des Types Nomenclature' => 'ROLE_MODIFIER_TYPE_NOMENCLATURE',
            'Supprimer des Types Nomenclature' => 'ROLE_SUPPRIMER_TYPE_NOMENCLATURE',
        ),
        'Gestion des Valeurs Nomenclature' => array(
            'Affichage des Valeurs Nomenclature' => 'ROLE_AFFICHER_VALEUR_NOMENCLATURE',
            'Ajouter des Valeurs Nomenclature' => 'ROLE_AJOUTER_VALEUR_NOMENCLATURE',
            'Modifier des Valeurs Nomenclature' => 'ROLE_MODIFIER_VALEUR_NOMENCLATURE',
            'Supprimer des Valeurs Nomenclature' => 'ROLE_SUPPRIMER_VALEUR_NOMENCLATURE',
        ),
        'Gestion des fiches' => array(
            'Affichage des fiches' => 'ROLE_AFFICHER_FICHE',
            'Ajouter des fiches' => 'ROLE_AJOUTER_FICHE',
            'Modifier des fiches' => 'ROLE_MODIFIER_FICHE',
            'Supprimer des fiches' => 'ROLE_SUPPRIMER_FICHE',
        ),
        'Gestion des choix' => array(
            'Affichage des choix' => 'ROLE_AFFICHER_CHOIX',
            'Ajouter des choix' => 'ROLE_AJOUTER_CHOIX',
            'Modifier des choix' => 'ROLE_MODIFIER_CHOIX',
            'Supprimer des choix' => 'ROLE_SUPPRIMER_CHOIX',
        ),
        'Gestion des questions' => array(
            'Affichage des questions' => 'ROLE_AFFICHER_QUESTION',
            'Ajouter des questions' => 'ROLE_AJOUTER_QUESTION',
            'Modifier des questions' => 'ROLE_MODIFIER_QUESTION',
            'Supprimer des questions' => 'ROLE_SUPPRIMER_QUESTION',
        ),
        'Gestion des sous-items' => array(
            'Affichage des sous-items' => 'ROLE_AFFICHER_SOUSITEM',
            'Ajouter des sous-items' => 'ROLE_AJOUTER_SOUSITEM',
            'Modifier des sous-items' => 'ROLE_MODIFIER_SOUSITEM',
            'Supprimer des sous-items' => 'ROLE_SUPPRIMER_SOUSITEM',
        ),

        'Gestion des items' => array(
            'Affichage des items' => 'ROLE_AFFICHER_ITEM',
            'Ajouter des items' => 'ROLE_AJOUTER_ITEM',
            'Modifier des items' => 'ROLE_MODIFIER_ITEM',
            'Supprimer des items' => 'ROLE_SUPPRIMER_ITEM',
        ),

        'Gestion des sites' => array(
            'Affichage des sites' => 'ROLE_AFFICHER_SITE',
            'Ajouter des sites' => 'ROLE_AJOUTER_SITE',
            'Modifier des sites' => 'ROLE_MODIFIER_SITE',
            'Supprimer des sites' => 'ROLE_SUPPRIMER_SITE',
        ),
        'Gestion des armoires' => array(
            'Affichage des armoires' => 'ROLE_AFFICHER_ARMOIRE',
            'Ajouter des armoires' => 'ROLE_AJOUTER_ARMOIRE',
            'Modifier des armoires' => 'ROLE_MODIFIER_ARMOIRE',
            'Supprimer des armoires' => 'ROLE_SUPPRIMER_ARMOIRE',
        ),
        'Noeuds d\'acceptances' => array(
            'Affichage des noeuds d\'acceptances' => 'ROLE_AFFICHER_NOEUDACCEPTANCE',
            'Ajouter des noeuds d\'acceptances' => 'ROLE_AJOUTER_NOEUDACCEPTANCE',
            'Modifier desnoeuds d\'acceptances' => 'ROLE_MODIFIER_NOEUDACCEPTANCE',
            'Supprimer des noeuds d\'acceptances' => 'ROLE_SUPPRIMER_NOEUDACCEPTANCE',
        ),
        'Gestion des stocks' => array(
            'Affichage des stocks' => 'ROLE_AFFICHER_STOCK',
            'Ajouter  des stocks' => 'ROLE_AJOUTER_STOCK',
            'Modifier  des stocks' => 'ROLE_MODIFIER_STOCK',
            'Supprimer  des stocks' => 'ROLE_SUPPRIMER_STOCK',
        )
    );

    

    

    public static function getRoles()
    {
        return self::$roles;
    }
}