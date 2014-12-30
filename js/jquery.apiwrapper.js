function doApiCall(post_data, callback)
{
	post_data = post_data + "&output=html";
	
	try {
		$.ajax({
		  type: 'GET',
		  url: "apiHandler.php",
		  data: post_data,
		  success: callback,
		  dataType: "json"
		});
	} catch (e) {}
}