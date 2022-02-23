<!-- Modal -->
<div class="modal fade" id="{{$modalid}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="admin_modal_title">{{$modaltitle}}</h5>
                <button type="button" id="{{$modalid}}_close_modal" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="" id="{{$formid}}" methode="post" enctype="multipart/form-data">
                    @csrf
                    {{$slot}}
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="{{$nameinputid}}">{{$namelabel}}<span class="text-deepred">*</span></label>
                            <input type="text" class="form-control" id="{{$nameinputid}}" autocomplete="off" placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="{{$emailinputid}}">{{$emaillabel}}<span class="text-deepred">*</span></label>
                            <input type="text" class="form-control" id="{{$emailinputid}}" autocomplete="off" placeholder="Enter Email">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="{{$mobileinputid}}">{{$mobilelabel}}<span class="text-deepred">*</span></label>
                            <input type="number" class="form-control" id="{{$mobileinputid}}" autocomplete="off" placeholder="Enter Mobile number">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <div class="d-flex ">
                                <div class="">
                                    <p>NOTE:(<span class="text-deepred">*</span>)Marked Items Are mandatory To fill</p>
                                </div>
                                <div class="ml-auto">
                                    <button type="submit" class="btn btn-outline-primary btn-sm "><small><i class="fas fa-plus"></i></small>Create</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
