var blogIg={
	init:function(){
		$$('div.article img').each(function(elm){
			elm.setStyle({
				'cursor' : 'pointer',
			});
			elm.observe('click',function(){
				window.open(elm.src,'_blogigimage');
			});
		});

		Event.observe(window, 'scroll', function() {
        		blogIg.topMenu();
		});

		var h=$('top').getHeight();
		$('wrap').setStyle({
			marginTop: h+10+'px',
		});
	},
	topMenu:function(){
		var ar=document.viewport.getScrollOffsets();
		if(ar.top > 50){
			if(!$("top").hasClassName("top"))
				$("top").addClassName("top");
		}else{
			if($("top").hasClassName("top"))
				$("top").removeClassName("top");
		}
	}
}

