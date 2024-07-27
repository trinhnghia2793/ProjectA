<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Constructor
    public function __construct()
    {
        
    }

    // Trang chủ (dashboard)
    public function index() {

        $config = $this->config();

        $template = 'backend.dashboard.home.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
        ));

    }

    private function config() {
        return [
            'js' => [
                // Plot
                'backend/js/plugins/flot/jquery.flot.js',
                'backend/js/plugins/flot/jquery.flot.tooltip.min.js',
                'backend/js/plugins/flot/jquery.flot.spline.js',
                'backend/js/plugins/flot/jquery.flot.resize.js',
                'backend/js/plugins/flot/jquery.flot.pie.js',
                'backend/js/plugins/flot/jquery.flot.symbol.js',
                'backend/js/plugins/flot/jquery.flot.time.js',

                // Peity
                'backend/js/plugins/peity/jquery.peity.min.js',
                'backend/js/demo/peity-demo.js',

                // Custom and plugin javascript
                'backend/js/inspinia.js',
                'backend/js/plugins/pace/pace.min.js',

                // Jvectormap
                'backend/js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js',
                'backend/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',

                // EayPIE
                'backend/js/plugins/easypiechart/jquery.easypiechart.js',

                // Sparkline
                'backend/js/plugins/sparkline/jquery.sparkline.min.js',

                // Sparkline demo data
                'backend/js/demo/sparkline-demo.js',
            ]
        ];
    }
}