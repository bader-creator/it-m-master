<?php

namespace App\Roles;

trait UserRolesTrait
{

    public static $roles = array(
        'Noeuds d\'acceptances' => array(
            'Affichage des noeuds d\'acceptances' => 'ROLE_AFFICHER_NOEUDACCEPTANCE',
            'Ajouter des noeuds d\'acceptances' => 'ROLE_AJOUTER_NOEUDACCEPTANCE',
            'Modifier des noeuds d\'acceptances' => 'ROLE_MODIFIER_NOEUDACCEPTANCE',
            'Supprimer des noeuds d\'acceptances' => 'ROLE_SUPPRIMER_NOEUDACCEPTANCE',
            'consulter un noeuds d\'acceptances' => 'ROLE_SHOW_NOEUDACCEPTANCE',
            
        ),
        'Gestion des stocks' => array(
            'Affichage des stocks' => 'ROLE_AFFICHER_STOCK',
            'Ajouter  des stocks' => 'ROLE_AJOUTER_STOCK',
            'Modifier  des stocks' => 'ROLE_MODIFIER_STOCK',
            'Supprimer  des stocks' => 'ROLE_SUPPRIMER_STOCK',
        ),
        'Gestion des tracabilités' => array(
            'Affichage des tracabilités' => 'ROLE_AFFICHER_TRACABILITY',
           
        ),
        'Gestion des missions' => array(
            'Affichage des missions' => 'ROLE_AFFICHER_MISSION',
            'Ajouter  des missions' => 'ROLE_AJOUTER_MISSION',
            'Modifier  des missions' => 'ROLE_MODIFIER_MISSION',
            'Supprimer  des missions' => 'ROLE_SUPPRIMER_MISSION',
            'Ajouter  des materiels' => 'ROLE_AJOUTER_MATERIEL',
            'Modifier  des materiels' => 'ROLE_MODIFIER_MATERIEL',
            'Supprimer  des materiels' => 'ROLE_SUPPRIMER_MATERIEL',
            'Accorder  des affectations' => 'ROLE_AJOUTER_AFFECTATION',
            'Raccorder  des affectations' => 'ROLE_MODIFIER_AFFECTATION',
            'supprimer  des affectations' => 'ROLE_SUPPRIMER_AFFECTATION',

        ),
    );


    public static function getRoles()
    {
        return self::$roles;
    }
}