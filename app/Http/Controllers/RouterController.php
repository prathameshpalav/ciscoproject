<?php

namespace App\Http\Controllers;

use App\Http\Requests\InsertRouterRequest;
use App\Http\Requests\UpdateRouterRequest;
use App\Router;
use Illuminate\Http\Request;

class RouterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('router.routerview');
    }

    /**
     * Return all routers in json
     *
     * @return Json
     */
    public function list(Request $request)
    {
        $routers = Router::where('sap_id', 'like', '%'.$request->sap_id.'%')
                            ->where('hostname', 'like', '%'.$request->hostname.'%')
                            ->where('mac_address', 'like', '%'.$request->mac_address.'%')
                            ->get();

        return response()->json([
            'status'    => 1,
            'data'      => $routers
        ]);
    }

    /**
     * Return single router details in json
     *
     * @return Json
     */
    public function show(Router $router)
    {
        if(!is_null($router)) {
            return response()->json([
                'status'    => 1,
                'data'      => $router
            ]);
        }

        return errorResponse();
    }

    /**
     * Create/Insert new router details
     *
     * @return Json
     */
    public function insert(InsertRouterRequest $request)
    {
        $router = new Router();

        $router->sap_id = $request->sap_id;
        $router->hostname = $request->hostname;
        $router->mac_address = $request->mac_address;

        if($router->save()) {
            return response()->json([
                'status'    => 1,
                'message'   => 'Router created successfully.'
            ]);
        }

        return errorResponse();
    }

    /**
     * Update existing router details
     *
     * @return Json
     */
    public function update($ip, UpdateRouterRequest $request)
    {
        $ipExistsForOtherRouter = Router::ip($ip)
                                        ->where('id', '!=', $request->id)
                                        ->exists();

        if( is_null($ipExistsForOtherRouter) ) {
            return errorResponse('Hostname/IP already exists for other router.');
        }

        $router = Router::ip($ip)->first();

        if( is_null($router) ) {
            return errorResponse('Invalid Hostname/IP.');
        }

        $router->sap_id = $request->sap_id;
        $router->hostname = $request->hostname;
        $router->mac_address = $request->mac_address;

        if($router->save()) {
            return response()->json([
                'status'    => 1,
                'message'   => 'Router updated successfully.'
            ]);
        }

        return errorResponse();
    }

    /**
     * Delete existing router details (soft delete) 
     *
     * @return Json
     */
    public function delete($ip)
    {
        $router = Router::ip($ip)->first();

        if( is_null($router) ) {
            return errorResponse('Invalid Hostname/IP.');
        }

        $deleted = $router->delete();

        if($deleted) {
            return response()->json([
                'status'    => 1,
                'message'   => 'Router deleted successfully.'
            ]);
        }

        return errorResponse();
    }
}
