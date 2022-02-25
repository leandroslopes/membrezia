<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "source/util/SystemUtil.php";
include_once "source/util/DataUtil.php";
include_once "source/dao/ModuloDAO.php";
include_once "source/modelo/Membro.php";

$moduloDAO = new ModuloDAO();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?= SystemUtil::TITLE_SYSTEM ?> | INÍCIO</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link type="image/x-icon" rel="icon" href="public/images/icons/favicon.png" />
		<link type="text/css" rel="stylesheet" href="public/css/style.css"/>
	</head>
	<body>
		<div class="corpo">
			<? include SystemUtil::URL_SYSTEM . "pageComponents/header.php?idModulo=inicio" ?>

			<div class="extras">
				<ul>
					<li><?= DataUtil::imprimirDataAtual() ?></li>
					<li>|</li>
					<li>
						<a href="sair.php">
							<img src="public/images/icons/sair.png"/>
							<label>Sair do sistema</label>
						</a>
					</li>
				</ul>
			</div> <br />

			<div class="modulos">
				<?= $moduloDAO->mostrarModulos($_SESSION["usuario"], false); ?>
			</div> <br />
		</div>
	</body>
</html>