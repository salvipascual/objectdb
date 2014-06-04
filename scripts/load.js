$(document).ready(function() {
	// starts toc scripts
	$('#content').jqTOC({
		tocWidth: 300,
		tocTitle:'Tabla de contenidos',
		tocStart: 1,
		tocEnd: 3,
		tocContainer:'toc-fixed',
		tocTopLink: '<img style="border:0px; width:8px; margin-right:5px;" src="images/top.png" />top'
	});

	// starts shjs script
	sh_highlightDocument();
	
	// iniciate the manu bevahior
	var menutitletext = $('#menutitle').html();
	$('img.menuelement').hover(function(){
		var elementtitle = $(this).attr('title');
		$('#menutitle').html(elementtitle);
	},function(){
		$('#menutitle').html(menutitletext);
	});
});