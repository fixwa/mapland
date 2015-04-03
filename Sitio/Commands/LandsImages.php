<?php
namespace Sitio\Commands;

use UnexpectedValueException;

class LandsImages
{
    const TABLE = 'lands_images';

    /**
     *
     * @throws UnexpectedValueException
     */
    public function create($data)
    {
        $image = \ORM::for_table(self::TABLE)->create();

        $image->land_id   = 0;
        $image->url       = $data['baseDir'] . $data['name'];
        $image->file_name = $data['name'];
        $image->user_id   = $_SESSION['id'];
        $image->temp_id   = $data['tempId'];

        $image->save();

        return $image->id;
    }

    public function getAllForLand($landId)
    {
        return \ORM::for_table(self::TABLE)
            ->where_raw('(`land_id` = ? )', [$landId])
            ->find_many();

    }


    /**
     * @param $landId
     * @param $tempId
     */
    public function updateTemporary($landId, $tempId)
    {
        $images = \ORM::for_table(self::TABLE)
            ->where_raw('(`temp_id` = ? AND `user_id` = ?)', array($tempId, $_SESSION['id']))
            ->find_many();

        foreach ($images as $image) {
            $image->set('temp_id', '');
            $image->set('land_id', $landId);
            $image->save();
        }
    }
}
