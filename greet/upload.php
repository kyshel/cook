

<!DOCTYPE html>
<html>
<head>
	<title>JQuery Template</title>
	<meta charset="utf-8">
	
</head>
<body>
	<input id="inp" type='file'>
	<p id="b64"></p>
	<img id="img" height="150">

	<img id="server_image" height="150">
	<div id="show"></div>
	<button id="btn">gray</button>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript">

		var API_PATH = "../chef/api.php";

		document.getElementById("inp").addEventListener("change", readFile);

		$('#btn').click(function(e) {
			var image = {
				"name": getFile().name,
				"content":document.getElementById("img").src
			};
			order(image,'gray');
		});

		function readFile() {		
			var file_input = document.getElementById("inp")
			if (file_input.files && file_input.files[0]) {
				var FR= new FileReader();
				var content = '';
				FR.addEventListener("load", function(e) {
					document.getElementById("img").src       = e.target.result;
					document.getElementById("b64").innerHTML = e.target.result;	
					var image = {
						"name": file_input.files[0].name,
						"content":e.target.result
					};
					deposit(image)

				}); 
				FR.readAsDataURL(file_input.files[0] );
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

	</script>



</body>
</html>