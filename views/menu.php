<?php
require_once('controles/acesso.php');
controle_acesso('menu');
?>
<div id='fundo_menu'>
	<div id='menu'>
		<ul>
			<li><a href='index.php'><span>PRINCIPAL</span></a></li>
			<?php foreach($menu as $m) { ?>
			<li><a href='#'><span><?php echo $m['menu']; ?></span></a>
				<ul>
					<?php
					$pag = explode(",",$m['paginas']);
					$end = explode(",",$m['enderecos']);
					foreach ($pag as $i => $p) {
						echo "<li><a href='?pag=views/".$end[$i]."'><span>&nbsp;".$p."</span></a></li>";
					}
					?>
				</ul>
			</li>
			<?php } ?>
			<!--<li><a href='#'><span>BACKUP</span></a>
				<ul>
					<li><a href='?pag=controles/backup.php&acao=realizar'><span>&nbsp;REALIZAR</span></a></li>
					<li><a href='javascript:restaurar_backup()'><span>&nbsp;RESTAURAR</span></a></li>
				</ul>
			</li>-->
			<li><a href='sair.php'><span>SAIR</span></a></li>
		</ul>
	</div>
</div>
<br>
<div class='usuario'><label class='label_usuario'><?php echo $_SESSION['usuario']; ?></label></div>