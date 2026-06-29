<script>

    function my_toaster(message = 'تم بنجاح ',type = 'success'){
        var color1 =  '#80c204';
        var color2 =  '#a0ff00';
        if (type == 'error'){
            color1 =  '#c20505';
            color2 =  '#ff0000';
        }
        else if (type == 'info'){
            color1 =  '#0522bf';
            color2 =  '#002aff';
        }
        else if (type == 'warning'){
            color1 =  '#c4a90a';
            color2 =  '#fce303';
        }

        Toastify({
            text: message,
            duration: 3000,
            // destination: "https://github.com/apvarun/toastify-js",
            newWindow: true,
            close: true,
            gravity: "top", // `top` or `bottom`
            position: "right", // `left`, `center` or `right`
            stopOnFocus: true, // Prevents dismissing of toast on hover
            style: {
                background: "linear-gradient(to right, "+color1+", "+color2+")",
            },
            onClick: function(){} // Callback after click
        }).showToast();
    }
    // my_toaster() //Calling

</script>

<script>
    @if(session()->has('message'))
    var type = "{{ session()->get('type') }}"
    var message = "{{ session()->get('message') }}"
    my_toaster(message,type)
    @endif
</script>
