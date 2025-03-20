<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
class BaseController extends Controller
{
    protected $website = 'admin';
    protected $view = null;
    protected $module = null;
    public $db;

    public function __construct($module){
        $this->module = $module;
        $this->view = $this->website . ".modules." . $module;
        $this->db = DB::table($module);
    }
    public function view_admin (string $page, array $data = []) {
        return view($this->view . "." . $page, $data);
    }

    public function route_admin(string $page, array $params = [], array $flash = [], $pageParam = null)
    {
        // Add page parameter if it is not null
        if ($pageParam !== null) {
            $params['page'] = $pageParam;
        }

        if (empty($flash)) {
            return redirect()->route($this->website . "." . $this->module . "." . $page, $params);
        }
        return redirect()->route($this->website . "." . $this->module . "." . $page, $params)->with($flash);
    }
}
