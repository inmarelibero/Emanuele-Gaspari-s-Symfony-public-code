<script type="text/javascript" language="javascript"> 
//<![CDATA[
           
	$(function() {		

		// initializes the offers list sortable box
		$("#list_offers").sortable({
			opacity: 							0.7,
			revert: 							true,
			cursor:								'move',
			connectWith: 					'#list_promotions',
			items:								'.article',
			
			forcePlaceholderSize:	true,
			forceHelperSize: 			true,
			helper: 							'clone',
			tolerance: 						'pointer',

			update: function() {
				updateLists();
				activate_links_add();
			}
		});

		// initializes the promotions list sortable
		$("#list_promotions").sortable({
			opacity: 							0.7,
			revert: 							true,
			cursor:								'move',
			connectWith: 					'#list_offers',
			items:								'.article',
			
			forcePlaceholderSize:	true,
			forceHelperSize:			true,
			helper:								'clone',
			tolerance:						'pointer'
		});

		// enables/disables the sortables behaviour when the maximun number of articles is reached
		toggleEnable(<?php echo sfConfig::get('app_offerte_promozioni_numero_massimo')?>);
		
		activate_links_add();
	});

	/**
	 *	click behaviour for the links to create an article
	 */
	function activate_links_add()
	{
		$('.aggiungi_offerta').click(function() {
			window.location ='<?php echo url_for('article/new?tipo=offerta', true)?>';
		});

		$('.aggiungi_promozione').click(function() {
			window.location ='<?php echo url_for('article/new?tipo=promozione', true)?>';
		});
	}
	

	/**
	 *	updates the lists when a sortable event occurs
	 */
	function updateLists()
	{
		toggleEnable(<?php echo sfConfig::get('app_offerte_promozioni_numero_massimo')?>);
		
		var data_offers			=  $('#form_offers').serializeArray();
		var data_promotions	=  $('#form_promotions').serializeArray();

		// makes ajax call to update database
		$.ajax({
			url: '<?php echo url_for('article/saveOrderAndType', true)?>',
			data:{
				offers:			data_offers,
				promotions:	data_promotions
			}
		});
		
	}

	//]]>
</script>



<?php if ($sf_user->hasFlash('notice')):?>
	<div class="notice">
		<?php echo $sf_user->getFlash('notice')?>
	</div>
<?php endif;?>


<div id="list_articles">

	<div class="title">
		Offers:
	</div>
	
	<div class="list">
		<form id="form_offers">	
			<ul id="list_offers">
				<?php foreach ($arr_offers as $article):?>
					<?php include_partial('row_article', array('article'=>$article))?>
				<?php endforeach;?>
			</ul>
		</form>
	</div>


	<div class="title">
		Promotions:
	</div>
	
	<div class="list">
		<form id="form_promotions">
			<ul id="list_promotions"">
				<?php foreach ($arr_promotions as $article):?>
					<?php include_partial('row_article', array('article'=>$article))?>
				<?php endforeach;?>
			</ul>
		</form>
	</div>
	
</div>