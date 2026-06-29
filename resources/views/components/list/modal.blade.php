@props(['name' => 'الرئيسية' , 'save_button' => true ])


<div class="modal fade" id="Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg mw-650px">
        <div class="modal-content" id="modalContent">
            <div class="modal-header">
                <h2>{{$name}}</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" style="cursor: pointer" data-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)"
                                  fill="black"/>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"/>
                        </svg>
                    </span>
                </div>
            </div>
            <!--begin::Modal body-->
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-3" id="form-load">

            </div>
            <!--end::Modal body-->
            <div class="modal-footer">
                @if($save_button)
                    <div class=" ">
                        <input  form="form" value="حفظ" type="submit" id="submit" class="btn btn-primary " style="width: 100px">
                    </div>
                @endif
                <div class=" ">
                    <input type="button" class="btn btn-light me-3 " id="close_model" style="width: 100px" value="غلق">
                </div>
            </div>
        </div>

    </div>
</div>
