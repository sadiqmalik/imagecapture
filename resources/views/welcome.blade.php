<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camera Capture</title>
    <style>
        body {
            background-color: #f8f9fa; /* Set a light background color */
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff; /* Set a white background color for the container */
            padding: 20px; /* Add some padding to the container */
            border-radius: 10px; /* Add border radius for a rounded appearance */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add a subtle box shadow */
            width: 400px; /* Set a fixed width for the container */
        }
        #my_camera {
            border: 5px solid #007bff; /* Add a blue border around the camera */
            border-radius: 10px; /* Add border radius for a rounded appearance */
            margin-bottom: 20px; /* Add some bottom margin */
        }
        input[type="button"],
        button.btn-success {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        input[type="button"]:hover,
        button.btn-success:hover {
            background-color: #218838;
        }
        #results {
            text-align: center; /* Center the captured image text */
        }
        #constituencyname {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }
        .response-message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
    </style>
</head>
<body>
<div class="container">
    <form id="cameraForm" method="POST" action="{{ route('camera.capture') }}">
        @csrf
        <div id="my_camera"></div>
        <br/>
        <input type="button" value="Take Snapshot" onClick="take_snapshot()">
        <input type="hidden" name="image" class="image-tag">
        <div id="results">Your captured image will appear here...</div>
        <hr/>
        <label for="constituencyname">Constituency Name</label>
        <input type="text"
               id="constituencyname" name="constituencyname"
               placeholder="Enter Constituency e.g. NA01, PP01">
        <div id="constituency-error" style="color: red;"></div>
        <div id="success-message"></div>
        <button type="button" id="uploadBtn" class="btn btn-success">Upload</button>
    </form>
</div>

<!-- Include the Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script type="text/javascript">
    Webcam.set({
                   width: 400,
                   height: 300,
                   image_format: 'jpeg',
                   jpeg_quality: 90
               });

    Webcam.attach('#my_camera');

    function take_snapshot() {
        Webcam.snap(function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="' + data_uri + '"/>';
        });
    }

    $(document).ready(function() {
        $("#uploadBtn").click(function() {
            var constituencyName = document.getElementById('constituencyname').value;

            // Validate that it is not empty and has exactly 4 digits
            if (constituencyName.trim() === '' || !/^[a-zA-Z0-9]{4,}$/.test(constituencyName)) {
                document.getElementById('constituency-error').innerHTML = 'Please enter a valid 4-digit constituency code.';
            } else {
                document.getElementById('constituency-error').innerHTML = '';

                // Serialize the form data
                var formData = $("#cameraForm").serialize();

                // Make an Ajax request using fetch
                fetch("{{ route('camera.capture') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                    .then(response => response.text())
                    .then(data => {
                        // Display the response under the upload button
                        $("#success-message").html('<div class="alert alert-success response-message">' + data + '</div>');
                    })
                    .catch(error => {
                        // Handle errors if needed
                        console.log(error);
                    });
            }
        });
    });
</script>
</body>
</html>
