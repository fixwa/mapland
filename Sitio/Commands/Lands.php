<?php
namespace Sitio\Commands;

use UnexpectedValueException;

/**
 * Class Lands. Has methods to get data from the Database.
 *
 * @package Sitio\Commands
 */
class Lands
{
    public function __construct()
    {
        $this->images = new LandsImages();
    }

    /**
     * Devuelve un array con todos los usuarios en la tabla.
     *
     * @return mixed
     */
    public function listAll()
    {
        $lands = \ORM::for_table('lands')->order_by_desc('title')->find_many();

        return $lands;
    }

    /**
     * @param $id
     * @return bool|\ORM
     */
    public function find($id)
    {
        $land = \ORM::for_table('lands')->find_one((int)$id);

        if (!empty($land->id)) {
            $land->images = $this->images->getAllForLand($land->id);
        }

        return $land;
    }

    /**
     * Crea un nuevo usuario.
     *
     * @throws UnexpectedValueException
     */
    public function create()
    {
        $land = \ORM::for_table('lands')->create();

        $land->title         = $_POST['title'];
        $land->price         = $_POST['price'];
        $land->square_meters = $_POST['square_meters'];
        $land->description   = $_POST['description'];

        $land->latitude  = $_GET['lat'];
        $land->longitude = $_GET['lng'];
        $land->user_id   = $_SESSION['id'];
        $land->location  = '';

        $land->save();

        return $land->id;
    }
}
