<?php

namespace App\View\Components;

use Illuminate\View\Component;

class settings_modal extends Component
{
    public $modalid, $modaltitle, $formid, $inputid, $inputtype, $labelname, $btncreateid;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($modalid, $modaltitle, $formid, $inputid, $inputtype, $labelname, $btncreateid)
    {
        $this->modalid = $modalid;
        $this->modaltitle = $modaltitle;
        $this->formid = $formid;
        $this->inputid = $inputid;
        $this->inputtype = $inputtype;
        $this->labelname = $labelname;
        $this->btncreateid = $btncreateid;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.settings_modal');
    }
}
