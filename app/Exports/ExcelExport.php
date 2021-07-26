<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExcelExport implements FromView {
    private $viewFile;
    private $data;

    public function __construct($viewFile, $data) {
        $this->viewFile = $viewFile;
        $this->data = $data;
        }

        public function view(): View{
            
        return view($this->viewFile,$this->data);
    }

}
