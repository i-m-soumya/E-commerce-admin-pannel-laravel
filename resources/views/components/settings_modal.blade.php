<div class="modal fade" id="{{$modalid}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">{{$modaltitle}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="" id="{{$formid}}" methode="post" enctype="multipart/form-data">
                    @csrf
                    {{$slot}}
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="{{$inputid}}">{{$labelname}}</label>
                            <input type="{{$inputtype}}" class="form-control" id="{{$inputid}}" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-outline-primary btn-sm rounded-pill" id="{{$btncreateid}}">Create</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
