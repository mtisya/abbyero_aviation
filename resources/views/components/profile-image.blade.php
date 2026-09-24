<div class="text-center">
    <form id="profileImageForm"
          action="{{ route('profile.image.update') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <label for="profile_image" style="cursor:pointer;">

            <div class="rounded-circle border shadow-sm d-flex align-items-center justify-content-center position-relative"
                 style="width:120px;height:120px;overflow:hidden;background:#f8f9fa;">

                @if(auth()->user()->profile_image)

                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}"
                         class="w-100 h-100"
                         style="object-fit:cover;"
                         alt="Profile">

                    <!-- Camera Button -->
                    <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow"
                         style="width:35px;height:35px;">
                        <i class="bi bi-camera-fill"></i>
                    </div>

                @else

                    <!-- Add Photo -->
                    <div class="text-primary text-center">
                        <i class="bi bi-plus-circle-fill"
                           style="font-size:45px;"></i>

                        <div class="small mt-1">
                            Add Photo
                        </div>
                    </div>

                @endif

            </div>

        </label>


        <input type="file"
               id="profile_image"
               name="profile_image"
               class="d-none"
               accept="image/*">

    </form>
</div>


<script>
document.getElementById('profile_image')?.addEventListener('change', function () {

    if(this.files.length > 0){
        document.getElementById('profileImageForm').submit();
    }

});
</script>