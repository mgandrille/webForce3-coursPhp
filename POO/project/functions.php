<?php

require __DIR__.'/lib/Ship.php';

function getShips() {

    $ships = []; // Futur array d'objets vaisseaux Ships

    // On créée un Ship
    $ship1 = new Ship();
    $ship1->setName('Jedi Starfighter');
    $ship1->setWeaponPower(5);
    $ship1->setSpatiodriveBooster(15);
    $ship1->setStrength(30);

    // On ajoute le ship "$ship1" créé dans le tableau de vaisseaux, $ships.
    $ships['starfighter'] = $ship1;

    // On créée un deuxième Ship
    $ship2 = new Ship();
    $ship2->setName('X-Wing Fighter');
    $ship2->setWeaponPower(2);
    $ship2->setSpatiodriveBooster(2);
    $ship2->setStrength(70);

    $ships['x_wing_fighter'] = $ship2;

    // On créée un troisième Ship
    $ship3 = new Ship();
    $ship3->setName('Super Star Destroyer');
    $ship3->setWeaponPower(70);
    $ship3->setSpatiodriveBooster(0);
    $ship3->setStrength(500);

    $ships['super_star_destroyer'] = $ship3;

    // On créée un quatrième Ship
    $ship4 = new Ship();
    $ship4->setName('RZ1 A-Wing Interceptor');
    $ship4->setWeaponPower(4);
    $ship4->setSpatiodriveBooster(4);
    $ship4->setStrength(50);

    $ships['rz1_a_wing_interceptor'] = $ship4;

    // On retourne l'array de vaisseaux
    return $ships;
}

/**
 * L'algorithme de combat super complexe !
 *
 * @return array [winning_ship, losing_ship, used_jedi_powers]
 */
function battle(array $ship1, $ship1Quantity, array $ship2, $ship2Quantity)
{
    // Calcul de la résistance totale de chaque groupe (strength * nombre de combattants)
    $ship1Health = $ship1['strength'] * $ship1Quantity;
    $ship2Health = $ship2['strength'] * $ship2Quantity;

    // Par défaut, personne n'a utilisé le spatiodrive booster
    $ship1UsedSpatiodriveBoosters = false;
    $ship2UsedSpatiodriveBoosters = false;

    // Tant que les 2 groupes ont une résistance supérieure à 0, on combat :
    while ($ship1Health > 0 && $ship2Health > 0) {
        // On vérifie à chaque tour si quelqu'un a utilisé le spatiodrive booster
        // en appelant la fonction usedSpatiodriveBoosters()

        if (usedSpatiodriveBoosters($ship1)) {
            $ship2Health = 0;
            $ship1UsedSpatiodriveBoosters = true;
            // Si le spatiodrive booster a été utilisé, on break la boucle while
            break;
        }
        if (usedSpatiodriveBoosters($ship2)) {
            $ship1Health = 0;
            $ship2UsedSpatiodriveBoosters = true;
            // Si le spatiodrive booster a été utilisé, on break la boucle while
            break;
        }

        // Si pas de spatiodrive booster, on poursuit le combat :
        // On soustrait la force de chaque groupe à la résistance de l'autre groupe
        $ship1Health = $ship1Health - ($ship2['weapon_power'] * $ship2Quantity);
        $ship2Health = $ship2Health - ($ship1['weapon_power'] * $ship1Quantity);
    }

    // Si les 2 groupes tombent à 0 au même tour :
    if ($ship1Health <= 0 && $ship2Health <= 0) {
        $winningShip = null;
        $losingShip = null;
        $usedSpatiodriveBoosters = $ship1UsedSpatiodriveBoosters || $ship2UsedSpatiodriveBoosters;
    } elseif ($ship1Health <= 0) {
        // Si la résistance du groupe 1 tombe à 0
        $winningShip = $ship2;
        $losingShip = $ship1;
        $usedSpatiodriveBoosters = $ship2UsedSpatiodriveBoosters;
    } else {
        // Sinon, c'est la résistance du groupe 2 qui tombe à 0
        $winningShip = $ship1;
        $losingShip = $ship2;
        $usedSpatiodriveBoosters = $ship1UsedSpatiodriveBoosters;
    }

    return array(
        'winning_ship' => $winningShip,
        'losing_ship' => $losingShip,
        'used_spatiodrive_boosters' => $usedSpatiodriveBoosters,
    );
}

function usedSpatiodriveBoosters(array $ship)
{
    $spatiodriveBoostersProbability = $ship['spatiodrive_booster'];
    // On tire un nombre entre 1 et 100, s'il est inférieur à la probabilité du booster Spatiodrive, alors on retourne true :
    return mt_rand(1, 100) <= $spatiodriveBoostersProbability;
}