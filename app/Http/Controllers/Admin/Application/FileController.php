<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Application;

use App\Http\Controllers\Controller;
use App\Models\File;

class FileController extends Controller
{
    protected $viewType = 'admin';

    public function __construct()
    {
        parent::__construct();
        $this->theme = 'application.file';
    }

    public function indexAction()
    {
        $this->setHeader('title', 'Uploaded Files');
        return $this->view('index', ['files' => (new File())->fetch(requestValues('search'))]);
    }

    public function destroyAction($id)
    {
        (new File())->remove($id);
        return $this->json('File is successfully deleted.');
    }
}
