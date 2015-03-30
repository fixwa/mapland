<?php
namespace Sitio;

use Sitio\Commands\Lands;
use System\BaseController;

class Home extends BaseController
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
        $this->view->allLands = $this->lands->listAll();
    }
}
