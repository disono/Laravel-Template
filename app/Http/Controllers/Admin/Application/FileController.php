<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Http\Controllers\Admin\Application;

use App\Http\Controllers\Controller;
use App\Models\Vendor\Facades\File;

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
        $this->setHeader('title', 'Files');
        $file = File::self();
        $file->enableSearch = true;
        $file->setWritableColumn('created_at');
        return $this->view('index', ['files' => $file->fetch(requestValues('search|pagination_show|file_name|title|type|created_at'))]);
    }

    public function destroyAction($id)
    {
        File::remove($id);
        return $this->json('File is successfully deleted.');
    }
}
