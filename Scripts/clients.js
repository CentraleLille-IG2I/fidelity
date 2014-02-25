$(function() {
	$('#recherche').keyup(function() {
		console.log($('#recherche').val());
		$('tr:gt(0)').each(function() {
			if($(this).html().match($('#recherche').val()) != null)
			{
				console.log($(this).attr('id') + ": Ok!");
				$(this).css('display','table row');
			}
			else
			{
				console.log($(this).attr('id') + ": Pas ok!");
				$(this).css('display','none');
			}
		});
	});
});