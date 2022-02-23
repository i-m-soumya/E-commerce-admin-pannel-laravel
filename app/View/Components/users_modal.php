<?php

namespace App\View\Components;

use Illuminate\View\Component;

class users_modal extends Component
{
    public $modalid,$modaltitle,$formid,$nameinputid,$namelabel,$emailinputid,$emaillabel,$mobileinputid,$mobilelabel;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($modalid,$modaltitle,$formid,$nameinputid,$namelabel,$emailinputid,$emaillabel,$mobileinputid,$mobilelabel)
    {
        $this->modalid=$modalid;
        $this->modaltitle=$modaltitle;
        $this->formid=$formid;
        $this->nameinputid=$nameinputid;
        $this->namelabel=$namelabel;
        $this->emailinputid=$emailinputid;
        $this->emaillabel=$emaillabel;
        $this->mobileinputid=$mobileinputid;
        $this->mobilelabel=$mobilelabel;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.users_modal');
    }
}
