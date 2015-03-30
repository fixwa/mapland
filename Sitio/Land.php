<?php
namespace Sitio;

use Sitio\Commands\Lands;
use System\BaseController;
use UnexpectedValueException;

class Land extends BaseController
{
    /**
     * @var Lands
     */
    protected $lands;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->lands = new Lands();
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

        $this->land = $this->lands->find($id);
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
                header('Location: /land/view/' . (int) $land);
                exit;
            }
        }
    }


    public function uploadImage()
    {
        $this->view->disableView();


        $storage = new \Upload\Storage\FileSystem(__DIR__ . '/../Assets/Uploads/');
        $file = new \Upload\File('images', $storage);

        // Optionally you can rename the file on upload
        $new_filename = uniqid();
        $file->setName($new_filename);

        // Validate file upload
        // MimeType List => http://www.webmaster-toolkit.com/mime-types.shtml
        $file->addValidations(array(
            // Ensure file is of type "image/png"
            new \Upload\Validation\Mimetype('image/png'),

            //You can also add multi mimetype validation
            //new \Upload\Validation\Mimetype(array('image/png', 'image/gif'))

            // Ensure file is no larger than 5M (use "B", "K", M", or "G")
            new \Upload\Validation\Size('5M')
        ));

        // Access data about the file that has been uploaded
        $data = array(
            'name'       => $file->getNameWithExtension(),
            'extension'  => $file->getExtension(),
            'mime'       => $file->getMimetype(),
            'size'       => $file->getSize(),
            'md5'        => $file->getMd5(),
            'dimensions' => $file->getDimensions()
        );

        // Try to upload file
        try {
            // Success!
            $file->upload();
        } catch (\Exception $e) {
            // Fail!
            $errors = $file->getErrors();
        }
    }
}
