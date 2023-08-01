@extends('auth.index')
@section('content')
    <div class="row mx-auto profile">
        <div class="col-12">
            <h2>حساب کاربری</h2>
            <div class="col-12">
                <div class="profile-money mx-auto">
                    <div class="pm-title">موجودی کیف پول</div>
                    <div class="row align-items-center pm-box">
                        <div class="col-7 text-left">
                            <div class="pm-price">{{ fa_number(number_format(auth()->guard('clients')->user()->wallet)) }} </div>
                        </div>
                        <div class="col-5 text-center">
                            <div>تومان</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-12">
            <div class="profileIn mx-auto">
                <div class="dFlex">
                    <img src="{{ $client->image }}" class="imgProfile" id="avatar_img" alt=""/>
                </div>

                <div class="dFlex">
                    <form method="post" action="{{ route('profile') }}">
                        @csrf
                        <input type="file" class="file-input" id="upload" name="avatarPicture" hidden>
                        <label class="labelUpload mx-auto" for="upload">
                            آپلود عکس جدید
                        </label>

                        <label for="name">نام شما</label>
                        <input type="text" name="name" id="" value="{{ $client->name }}">
                        <img src="{{asset('assets/photo/pen.svg')}}" class="pen" alt=""/>
                        @error('name')
                            <br>
                            <label style="color:red;">
                                {{ $message }}
                            </label>
                        @enderror

                        <label for="phone">شماره موبایل شما</label>
                        <input type="number" name="phone" id="" value="{{ $client->phone }}">
                        <img src="{{asset('assets/photo/pen.svg')}}" class="pen" alt=""/>
                        @error('phone')
                            <br>
                            <label style="color:red;">
                                {{ $message }}
                            </label>
                        @enderror
                        <button type="submit" class="btn">ثبت تغییرات</button>
                    </form>
                </div>
            </div>

            <div class="text-center mb-5">
                <a href="{{ route('logout') }}" class="exit">خروج از حساب</a>
            </div>

        </div>
    </div>

    <script>

        document.getElementById('upload').addEventListener('change', upload_file, false);

        function upload_file(evt) {

            var tgt = evt.target || window.event.srcElement,
                files = tgt.files;

            var files = evt.target.files;

            // FileReader support
            if (FileReader && files && files.length) {
                var fr = new FileReader();
                fr.onload = function () {
                    $("#avatar_img").attr("src", fr.result);
                }
                fr.readAsDataURL(files[0]);
            }


            // Loop through the FileList and render image files as thumbnails.
            for (var i = 0, f; f = files[i]; i++) {

                // Only process image files.
                if (!f.type.match('image.*')) {
                    continue;
                }

                var reader = new FileReader();

                // Closure to capture the file information.
                reader.onload = (function (theFile) {
                    return function (e) {
                        // Render thumbnail.

                        $("#profile_image").attr("src", e.target.result).css("width", "100%");

                        var form = new FormData();

                        const token = "{{ csrf_token() }}";

                        form.append("_token", token);

                        form.append(evt.target.name, theFile);

                        console.log(form.get('_token'));

                        var settings = {
                            "async": true,
                            "crossDomain": true,
                            "url": "{{ route('avatar_submit') }}",
                            "method": "POST",
                            "headers": {
                                "cache-control": "no-cache",
                            },
                            "processData": false,
                            "contentType": false,
                            "mimeType": "multipart/form-data",
                            "data": form
                        }

                        $.ajax(settings).done(function (response) {
                            console.log(response);
                            // location.reload();
                        });

                    };
                })(f);

                // Read in the image file as a data URL.
                reader.readAsDataURL(f);
            }
        }

    </script>

@endsection
