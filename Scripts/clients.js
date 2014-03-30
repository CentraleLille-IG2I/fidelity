$(function() {
	$('#recherche').keyup(function() {
		$('#liste tr:gt(0)').each(function() { // Pour chaque ligne du tableau (hors ligne d'en-tête)
			if($(this).html().match(new RegExp("<td>[^<]*"+$('#recherche').val()+"[^<]*</td>",'i')) != null) // Si la valeur du champ de recherche est trouvée
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

function redirection(idClient){
	document.location.href="index.php?page=clients&mode=fiche&id="+idClient;
}