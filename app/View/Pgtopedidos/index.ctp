
<style>
/* ============================================================================================================================
== BUBBLE WITH AN ISOCELES TRIANGLE
** ============================================================================================================================ */

/* THE SPEECH BUBBLE
------------------------------------------------------------------------------------------------------------------------------- */

.triangle-isosceles {
  position:relative;
  padding:15px;
  margin:1em 0 3em;
  color:#000;
  background:#f3961c; /* default background for browsers without gradient support */
  /* css3 */
  background:-webkit-gradient(linear, 0 0, 0 100%, from(#f9d835), to(#f3961c));
  background:-moz-linear-gradient(#f9d835, #f3961c);
  background:-o-linear-gradient(#f9d835, #f3961c);
  background:linear-gradient(#f9d835, #f3961c);
  -webkit-border-radius:10px;
  -moz-border-radius:10px;
  border-radius:10px;
}

/* Variant : for top positioned triangle
------------------------------------------ */

.triangle-isosceles.top {
  background:-webkit-gradient(linear, 0 0, 0 100%, from(#f3961c), to(#f9d835));
  background:-moz-linear-gradient(#f3961c, #f9d835);
  background:-o-linear-gradient(#f3961c, #f9d835);
  background:linear-gradient(#f3961c, #f9d835);
}

/* Variant : for left/right positioned triangle
------------------------------------------ */

.triangle-isosceles.left {
  margin-left:50px;
  background:#f3961c;
}

/* Variant : for right positioned triangle
------------------------------------------ */

.triangle-isosceles.right {
  margin-right:50px;
  background:#f3961c;
}

/* THE TRIANGLE
------------------------------------------------------------------------------------------------------------------------------- */

/* creates triangle */
.triangle-isosceles:after {
  content:"";
  position:absolute;
  bottom:-15px; /* value = - border-top-width - border-bottom-width */
  left:50px; /* controls horizontal position */
  border-width:15px 15px 0; /* vary these values to change the angle of the vertex */
  border-style:solid;
  border-color:#f3961c transparent;
  /* reduce the damage in FF3.0 */
  display:block;
  width:0;
}

/* Variant : top
------------------------------------------ */

.triangle-isosceles.top:after {
  top:-15px; /* value = - border-top-width - border-bottom-width */
  right:50px; /* controls horizontal position */
  bottom:auto;
  left:auto;
  border-width:0 15px 15px; /* vary these values to change the angle of the vertex */
  border-color:#f3961c transparent;
}

/* Variant : left
------------------------------------------ */

.triangle-isosceles.left:after {
  top:16px; /* controls vertical position */
  left:-50px; /* value = - border-left-width - border-right-width */
  bottom:auto;
  border-width:10px 50px 10px 0;
  border-color:transparent #f3961c;
}

/* Variant : right
------------------------------------------ */

.triangle-isosceles.right:after {
  top:16px; /* controls vertical position */
  right:-50px; /* value = - border-left-width - border-right-width */
  bottom:auto;
  left:auto;
  border-width:10px 0 10px 50px;
  border-color:transparent #f3961c;
}
.spanUsuario{



}

/* THE TRIANGLE Alt
------------------------------------------------------------------------------------------------------------------------------- */

/* creates triangle */
.triangle-isoscelesalt:after {
  content:"";
  position:absolute;
  bottom:-15px; /* value = - border-top-width - border-bottom-width */
  left:50px; /* controls horizontal position */
  border-width:15px 15px 0; /* vary these values to change the angle of the vertex */
  border-style:solid;
  border-color:#ccc transparent;
  /* reduce the damage in FF3.0 */
  display:block;
  width:0;
}

/* Variant : top
------------------------------------------ */

.triangle-isoscelesalt.top:after {
  top:-15px; /* value = - border-top-width - border-bottom-width */
  right:50px; /* controls horizontal position */
  bottom:auto;
  left:auto;
  border-width:0 15px 15px; /* vary these values to change the angle of the vertex */
  border-color:#ccc transparent;
}

/* Variant : left
------------------------------------------ */

.triangle-isoscelesalt.left:after {
  top:16px; /* controls vertical position */
  left:-50px; /* value = - border-left-width - border-right-width */
  bottom:auto;
  border-width:10px 50px 10px 0;
  border-color:transparent #ccc;
}

/* Variant : right
------------------------------------------ */

.triangle-isoscelesalt.right:after {
  top:16px; /* controls vertical position */
  right:-50px; /* value = - border-left-width - border-right-width */
  bottom:auto;
  left:auto;
  border-width:10px 0 10px 50px;
  border-color:transparent #ccc;
}


</style>
<div class="mensagens index">
	<h2><?php echo __('Mensagens'); ?></h2>
	<?php
		foreach($mensagens as $mensagen){
		if($mensagen['Mensagen']['sender']==0){

			echo '<p class="triangle-isosceles">'.$mensagen['Mensagen']['msg'].'</p>';
		}else{
			echo '<span class="spanUsuario">'.$mensagen['Cliente']['username'].'</span>';
			echo '<p class="triangle-isosceles top">'.$mensagen['Mensagen']['msg'].'</p>';
		}

	?>


	<?php
		}
	?>
	<span>Atendente</span>
	<p class="triangle-isosceles"> teste chat </p>

	<pre>
	<?php
		print_r($mensagens);
	?>
	</pre>
</div>
