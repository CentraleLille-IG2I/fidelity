$(function() {
	$('#recherche').keyup(function() {
		$('#liste tr:gt(0)').each(function() { // Pour chaque ligne du tableau (hors ligne d'en-tête)
			if($(this).html().match(new RegExp($('#recherche').val(),'i')) != null) // Si la valeur du champ de recherche est trouvée
			{
				$(this).css('display','table row'); // Alors on affiche
			}
			else
			{
				$(this).css('display','none'); // Sinon on cache
			}
		});
	});
});