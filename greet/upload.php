

<!DOCTYPE html>
<html>
<head>
	<title>Cook Greet</title>
	<meta charset="utf-8">
	<style type="text/css">
		.is-quiet{
			display: none;
		}
		.is-active{
			display: block;
		}

		.loader-spinner {
			border: 5px solid #f3f3f3; /* Light grey */
			border-top: 5px solid #555; /* Blue */
			border-radius: 50%;
			width: 20px;
			height: 20px;
			animation: spin 1s linear infinite;
			display: inline-block;
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}

	</style>
	
</head>
<body>


	<input id="inp" type='file'>

	<div id="loader" class="is-quiet">
		<br>
		<div class="loader-spinner"></div>
		<span id="loader-text">lala</span>
	</div>

	<div id="bs64"></div>
	<img id="img" height="150">

	<img id="server_image" height="150">
	<div id="show"></div>
	<button id="btn">gray</button>
	<button id="loader-switch">loader_switch</button>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript">

		var API_PATH = "../chef/api.php";

		document.getElementById("inp").addEventListener("change", readFile);




		function readFile() {		
			var file_input = document.getElementById("inp")
			if (file_input.files && file_input.files[0]) {
				var FR= new FileReader();
				var content = '';
				FR.addEventListener("load", function(e) {
					document.getElementById("img").src       = e.target.result;
					//document.getElementById("bs64").innerHTML= e.target.result;
					console.log('display finish')

					showLoader('Loaded image, uploading ...');
					var image = {
						"name": file_input.files[0].name,
						"content":e.target.result
					};
					deposit(image);
				});
				FR.readAsDataURL(file_input.files[0]);

				FR.onloadstart = function(e){
					showLoader('Loading image...');
				};
				FR.onloadend = function(e){
					// hideLoader();

					// var image = {
					// 	"name": getFile().name,
					// 	"content":document.getElementById("img").src 
					// };
					// showLoader('Loaded image, uploading ...');
					// deposit(image);
					
					
				};

			}
		}



		function deposit(image) {
			//console.log(image)
			var path = API_PATH + "/deposit"

			$.ajax({
				url: path,
				type: 'PUT',
				dataType: "html",
				data: JSON.stringify(image),
			}).success( function(response,status,xhr) {
				console.log('xhr');
				console.log(xhr);
				console.log('response');
				console.log(response);

				console.log('deposit finish')
				hideLoader();

				if (status_code = 201) {
					
				}else{
					alert('Error occured!');
				}

			}).error( function(e) {
				console.log('e');
				console.log(e);
			});
		}



		function order(image,trick) {
			//console.log(image)
			var path = API_PATH + "/tricks/" + trick

			$.ajax({
				url: path,
				type: 'PUT',
				dataType: "html",
				data: JSON.stringify(image),
			}).success( function(response,status,xhr) {
				console.log('xhr');
				console.log(xhr);
				console.log('response');
				console.log(response);
				//status_code=xhr.status;
				//status_text=xhr.statusText;
				if (status_code = 201) {
					
				}else{
					alert('Error occured!');
				}
			}).error( function(e) {
				console.log('e');
				console.log(e);
			});
		}




		function getFile(){
			var files = document.getElementById("inp").files;
			if (files.length > 1){
				return 'please upload one file only!';
			} 
			return files[0];
		}

		function showLoader($text){
			$('#loader-text').html($text);
			$('#loader').removeClass('is-quiet');
			$('#loader').addClass('is-active');
		}
		function hideLoader(){
			$('#loader').removeClass('is-active');
			$('#loader').addClass('is-quiet');
		}

		$('#loader-switch').click(function(e) {
			$('#loader').removeClass('is-quiet');
			$('#loader').addClass('is-active');
		});

		$('#btn').click(function(e) {
			var image = {
				"name": getFile().name,
				"content":document.getElementById("img").src
			};
			order(image,'gray');
		});



	</script>



</body>
</html>