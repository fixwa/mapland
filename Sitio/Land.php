<?php
namespace Sitio;

use Sitio\Commands\Lands;
use Sitio\Commands\LandsImages;
use System\BaseController;
use UnexpectedValueException;

class Land extends BaseController
{
    /**
     * @var Lands
     */
    protected $lands;

    /**
     * @var LandsImages
     */
    protected $landsImages;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->lands = new Lands();

        $this->landsImages = new LandsImages();
    }

    /**
     * Muestra la Pagina
     */
    public function index()
    {
        $this->create();

        echo '<hr>';

        $lands = $this->lands->listAll();

        foreach ($lands as $land) {
            echo '<p>' . $land->title . '</p><br/>';
            echo '<p>' . $land->price . '</p><br/>';
            echo '<p>' . $land->square_meters . '</p><br/>';
            echo '<p>' . $land->description . '</p><br/>';
        }
        echo '<hr>';
    }

    public function view($id)
    {

        $this->view->land = $this->lands->find($id);
    }

    /**
     * Crea un Post
     */
    public function create()
    {
        if (!empty($_POST)) {

            $land = null;
            try {
                $land = $this->lands->create();
            } catch (UnexpectedValueException $e) {
                throw $e;
            }

            if (is_numeric($land)) {

                $this->landsImages->updateTemporary((int) $land, $this->tempId());
                header('Location: /land/view/' . (int) $land);
                exit;
            }
        }

        $tempId = $this->tempId(true);
    }


    public function uploadImage()
    {
        $this->view->disableView();

        $baseDir   = '/Assets/Uploads/Lands/' . $_SESSION['id'] . '/';
        $uploadDir = __DIR__ . '/..' . $baseDir;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $storage = new \Upload\Storage\FileSystem($uploadDir);
        $file = new \Upload\File('images', $storage);

        // Optionally you can rename the file on upload
        $new_filename = uniqid();
        $file->setName($new_filename);

        // Validate file upload
        // MimeType List => http://www.webmaster-toolkit.com/mime-types.shtml
        $file->addValidations(array(
            // Ensure filetype
            new \Upload\Validation\Mimetype(['image/png', 'image/pjpeg', 'image/jpeg', 'image/gif']),

            // Ensure file is no larger than 5M (use "B", "K", M", or "G")
            new \Upload\Validation\Size('15M')
        ));

        // Access data about the file that has been uploaded
        $data = array(
            'name'       => $file->getNameWithExtension(),
            'extension'  => $file->getExtension(),
            'mime'       => $file->getMimetype(),
            'size'       => $file->getSize(),
            'md5'        => $file->getMd5(),
            'dimensions' => $file->getDimensions(),
            'uploadDir'  => $uploadDir,
            'baseDir'    => $baseDir,
            'tempId'     => $this->tempId(),
        );

        // Try to upload file

        $file->upload();

        $this->landsImages->create($data);

    }

    private function tempId($regenerate = false)
    {
        if ($regenerate) {
            $_SESSION['temp-id'] = uniqid($_SESSION['id'] . '_');
        }

        return $_SESSION['temp-id'];
    }
}
